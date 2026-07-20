<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\Variation;
use App\Models\VariationGroup;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductsController extends Controller
{
    public function index()
    {
        try {
            $this->authorize('viewAny', Product::class);
            $tenant_id = auth()->user()->store->id;
            $products = Product::with(['category', 'productImages'])->where('tenant_id', $tenant_id)->paginate(10);

            return view('dashboard.products.index', compact('products'));
        } catch (\Throwable $th) {
            return view('dashboard.error');
        }
    }

    public function create()
    {
        try {
            $this->authorize('create', Product::class);
            $tenant_id = auth()->user()->store->id;
            $categories = Category::where('tenant_id', $tenant_id)->get();
            $variations = Variation::orderBy('name')->get();

            return view('dashboard.products.create', compact('categories', 'variations'));
        } catch (\Throwable $th) {
            return view('dashboard.error');
        }
    }

    public function store(Request $request)
    {
        $uploadedImages = [];
        try {
            $this->authorize('create', Product::class);
            $tenantId = auth()->user()->store->id;
            $request->validate(
                [
                    'name' => 'required',
                    'category_id' => [
                        'required',
                        Rule::exists('categories', 'id')->where('tenant_id', $tenantId),
                    ],
                    'price' => 'required|numeric|min:0',
                    'img' => 'nullable|array',
                    'img.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'status' => 'required|in:0,1',
                    'variation_id' => 'nullable|exists:variations,id',
                    'min_selection' => 'nullable|integer|min:0',
                    'max_selection' => 'nullable|integer|min:0|gte:min_selection',
                    'variations' => 'nullable|array',
                    'variations.*.value' => 'nullable|string|max:255',
                    'variations.*.additional_price' => 'nullable|numeric|min:0',
                ],
                [
                    'name.required' => 'O nome é obrigatório.',
                    'category_id.required' => 'Informe a categoria do produto',
                    'category_id.exists' => 'Categoria inválida.',
                    'price.required' => 'O preço é obrigatório',
                    'img.*.image' => 'O arquivo deve ser uma imagem.',
                    'img.*.mimes' => 'A imagem deve ser JPG, PNG ou WEBP.',
                    'img.*.max' => 'Cada imagem deve ter no máximo 2MB.',
                    'status.required' => 'O status é obrigatório.',
                    'status.in' => 'Selecione um status válido.',
                    'variation_id.exists' => 'Tipo de variação inválida.',
                    'min_selection.integer' => 'A seleção mínima deve ser um número inteiro.',
                    'min_selection.min' => 'A seleção mínima não pode ser negativa.',
                    'max_selection.integer' => 'A seleção máxima deve ser um número inteiro.',
                    'max_selection.min' => 'A seleção máxima não pode ser negativa.',
                    'max_selection.gte' => 'A seleção máxima deve ser maior ou igual à seleção mínima.',
                    'variations.*.additional_price.numeric' => 'O preço adicional deve ser um número válido.',
                    'variations.*.additional_price.min' => 'O preço adicional não pode ser negativo',

                ]
            );
            DB::beginTransaction();
            $product = Product::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'description' => $request->description,
                'status' => $request->status,
                'tenant_id' => $tenantId,
            ]);
            if ($request->hasFile('img')) {
                foreach ($request->file('img') as $file) {
                    if (! $file->isValid()) {
                        throw new \Exception('Erro ao fazer upload da imagem.');
                    }
                    $fileName = $file->hashName();
                    $filePath = $file->storeAs('products', $fileName);
                    if (! $filePath) {
                        throw new \Exception('Falha ao salvar imagem.');
                    }
                    $uploadedImages[] = $filePath;
                    ProductImage::create([
                        'tenant_id' => auth()->user()->store->id,
                        'product_id' => $product->id,
                        'img' => $filePath,
                    ]);
                }
            }
            if ($request->filled('variation_id') && $request->variations[0]['value'] != null) {
                $variationGroup = VariationGroup::create([
                    'tenant_id' => auth()->user()->store->id,
                    'product_id' => $product->id,
                    'variation_id' => $request->variation_id,
                    'min_selection' => $request->min_selection ?? 0,
                    'max_selection' => $request->max_selection ?? 1,
                ]);
                foreach ($request->variations as $variation) {
                    if (empty($variation['value'])) {
                        continue;
                    }
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'variation_group_id' => $variationGroup->id,
                        'value' => trim($variation['value']),
                        'additional_price' => ! empty($variation['additional_price']) ? $variation['additional_price'] : 0,
                        'status' => true,
                    ]);
                }
            }
            DB::commit();

            return redirect()->route('dashboard.products.index')->with('success', 'Produto criado com sucesso!');
        } catch (ValidationException $e) {
            DB::rollBack();

            return back()->withErrors($e->validator)->withInput();
        } catch (QueryException $e) {
            DB::rollBack();
            if (! empty($uploadedImages)) {
                foreach ($uploadedImages as $path) {
                    Storage::delete($path);
                }
            }

            return back()->withErrors(['error' => 'Ocorreu um erro na conexão com banco de dados.'])->withInput();
        } catch (\Throwable $th) {
            DB::rollBack();
            if (! empty($uploadedImages)) {
                foreach ($uploadedImages as $path) {
                    Storage::delete($path);
                }
            }

            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.'])->withInput();
        }
    }

    public function edit(string $id)
    {
        try {
            $tenant_id = auth()->user()->store->id;
            $categories = Category::where('tenant_id', $tenant_id)->get();
            $variations = Variation::orderBy('name')->get();
            $product = Product::with([
                'productImages',
                'variationGroups',
                'variationGroups.variation',
                'variationGroups.productVariations' => function ($query) {
                    $query->orderBy('value');
                },
            ])->where('tenant_id', $tenant_id)->findOrFail($id);
            $this->authorize('update', $product);

            return view('dashboard.products.edit', compact('product', 'categories', 'variations'));
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Produto não encontrado.']);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
        }

    }

    public function update(Request $request, string $id)
    {
        $uploadedImages = [];
        try {
            $tenantId = auth()->user()->store->id;
            $request->validate(
                [
                    'name' => 'required',
                    'category_id' => [
                        'required',
                        Rule::exists('categories', 'id')->where('tenant_id', $tenantId),
                    ],
                    'price' => 'required',
                    'img' => 'nullable|array',
                    'img.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'status' => 'required|in:0,1',
                    'promotional_price' => 'nullable|numeric|min:0|lt:price',
                    'variation_id' => 'nullable|exists:variations,id',
                    'min_selection' => 'nullable|integer|min:0',
                    'max_selection' => 'nullable|integer|min:0|gte:min_selection',
                    'variations' => 'nullable|array',
                    'variations.*.value' => 'nullable|string|max:255',
                    'variations.*.additional_price' => 'nullable|numeric|min:0',

                ],
                [
                    'name.required' => 'O nome é obrigatório.',
                    'category_id.required' => 'Informe a categoria do produto',
                    'category_id.exists' => 'Categoria inválida.',
                    'price.required' => 'O preço é obrigatório',
                    'img.*.image' => 'O arquivo deve ser uma imagem.',
                    'img.*.mimes' => 'A imagem deve ser JPG, PNG ou WEBP.',
                    'img.*.max' => 'Cada imagem deve ter no máximo 2MB.',
                    'status.required' => 'O status é obrigatório.',
                    'status.in' => 'Selecione um status válido.',
                    'promotional_price.numeric' => 'O preço promocional deve ser um número válido.',
                    'promotional_price.min' => 'O preço promocional não pode ser negativo.',
                    'promotional_price.lt' => 'O preço promocional deve ser menor que o preço normal.',
                    'variation_id.exists' => 'Tipo de variação inválida.',
                    'min_selection.integer' => 'A seleção mínima deve ser um número inteiro.',
                    'min_selection.min' => 'A seleção mínima não pode ser negativa.',
                    'max_selection.integer' => 'A seleção máxima deve ser um número inteiro.',
                    'max_selection.min' => 'A seleção máxima não pode ser negativa.',
                    'max_selection.gte' => 'A seleção máxima deve ser maior ou igual à seleção mínima.',
                    'variations.*.additional_price.numeric' => 'O preço adicional deve ser um número válido.',
                    'variations.*.additional_price.min' => 'O preço adicional não pode ser negativo',
                ]
            );
            $product = Product::where('tenant_id', $tenantId)->findOrFail($id);
            $this->authorize('update', $product);
            DB::beginTransaction();
            $product->name = $request->name;
            $product->category_id = $request->category_id;
            $product->price = $request->price;
            $product->promotional_price = $request->promotional_price;
            $product->description = $request->description;
            $product->status = $request->status;
            if ($request->hasFile('img')) {
                foreach ($request->file('img') as $file) {
                    if (! $file->isValid()) {
                        throw new \Exception('Erro ao fazer upload da imagem.');
                    }
                    $fileName = $file->hashName();
                    $filePath = $file->storeAs('products', $fileName);
                    if (! $filePath) {
                        throw new \Exception('Falha ao salvar imagem.');
                    }
                    $uploadedImages[] = $filePath;
                    ProductImage::create([
                        'tenant_id' => auth()->user()->store->id,
                        'product_id' => $product->id,
                        'img' => $filePath,
                    ]);
                }
            }
            VariationGroup::where('product_id', $product->id)->where('tenant_id', auth()->user()->store->id)->delete();
            if ($request->filled('variation_id') && $request->has_variation == 'on') {
                $variationGroup = VariationGroup::create([
                    'tenant_id' => auth()->user()->store->id,
                    'product_id' => $product->id,
                    'variation_id' => $request->variation_id,
                    'min_selection' => $request->min_selection ?? 0,
                    'max_selection' => $request->max_selection ?? 1,
                ]);
                if (! empty($request->variations)) {
                    foreach ($request->variations as $variation) {
                        if (empty($variation['value'])) {
                            continue;
                        }
                        ProductVariation::create([
                            'product_id' => $product->id,
                            'variation_group_id' => $variationGroup->id,
                            'value' => trim($variation['value']),
                            'additional_price' => ! empty($variation['additional_price']) ? $variation['additional_price'] : 0,
                            'status' => $variation['status'] ?? true,
                        ]);
                    }
                }
            }
            $product->save();
            DB::commit();

            return back()->with('success', 'Produto atualizado com sucesso!');
        } catch (ValidationException $e) {
            DB::rollBack();

            return back()->withErrors($e->validator)->withInput();
        } catch (QueryException $e) {
            DB::rollBack();
            if (! empty($uploadedImages)) {
                foreach ($uploadedImages as $path) {
                    Storage::delete($path);
                }
            }

            return back()->withErrors(['error' => 'Ocorreu um erro na conexão com banco de dados.'])->withInput();
        } catch (\Throwable $th) {
            DB::rollBack();
            if (! empty($uploadedImages)) {
                foreach ($uploadedImages as $path) {
                    Storage::delete($path);
                }
            }

            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.'])->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            $product = Product::with('productImages')->where('tenant_id', auth()->user()->store->id)->findOrFail($id);
            $this->authorize('delete', $product);
            DB::beginTransaction();
            $imgPaths = $product->productImages->pluck('img')->toArray();
            $product->delete();
            DB::commit();
            if (! empty($imgPaths)) {
                foreach ($imgPaths as $image) {
                    Storage::delete($image);
                }
            }

            return redirect()->route('dashboard.products.index')->with('success', 'Produto excluído com sucesso!');
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Produto não encontrado.']);
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
        }
    }

    public function deleteImage(string $id)
    {
        try {
            $productImage = ProductImage::where('tenant_id', auth()->user()->store->id)->findOrFail($id);
            DB::beginTransaction();
            $imgPath = $productImage->img;
            $productImage->delete();
            DB::commit();
            Storage::delete($imgPath);

            return back()->with('success', 'Imagem excluída com sucesso.');
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Imagem não encontrada.']);
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);

        }
    }
}
