<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\Variation;
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
        $products = Product::with(['category', 'productImages'])->paginate(10);
        return view('dashboard.products.index', compact('products'));
    }
    public function create()
    {
        $categories = Category::all();
        $variations = Variation::orderBy('name')->get();
        return view('dashboard.products.create', compact('categories', 'variations'));
    }
    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'name' => 'required',
                    'category_id' => 'required|exists:store.categories,id',
                    'price' => 'required|numeric|min:0',
                    'img' => 'nullable|array',
                    'img.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'status' => 'required|in:0,1',
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
                    
                ]
            );
            DB::beginTransaction();
            $product = Product::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'description' => $request->description,
                'status' => $request->status
            ]);
            if ($request->hasFile('img')) {
                foreach ($request->file('img') as $file) {
                    if(!$file->isValid()) {
                        throw new \Exception('Erro ao fazer upload da imagem.');
                    }
                    $fileName = $file->hashName();
                    $filePath = $file->storeAs('products',$fileName ,'public');
                    if (!$filePath) {
                        throw new \Exception('Falha ao salvar imagem.');
                    }
                    ProductImage::create([
                        'product_id' => $product->id,
                        'img' => $filePath
                    ]);
                }
            }
            $variation_values = array_filter($request->variation_values ?? []);
            if(!empty($variation_values) && $request->variation_id) {
                foreach ($variation_values as $value) {
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'variation_id' => $request->variation_id,
                        'value' => $value
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('dashboard.products.index')->with('success', 'Produto criado com sucesso!');
        } catch (ValidationException $e){
            DB::rollBack();
            return back()->withErrors($e->validator)->withInput();
        } catch (QueryException $e){
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocorreu um erro na conexão com banco de dados.'])->withInput();
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.'])->withInput();
        }
    }
    public function edit(string $id)
    {
         try {
            $categories = Category::all();
            $variations = Variation::orderBy('name')->get();
            $product = Product::with(['productImages', 'productVariations', 'productVariations.variation'])->findOrFail($id);
            return view('dashboard.products.edit',compact('product', 'categories', 'variations'));
        } catch (ModelNotFoundException $e){
            return back()->withErrors(['error' => 'Produto não encontrado.']);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
        }
        
    }
    public function update(Request $request, string $id)
    {
        try {
            $request->validate(
                [
                    'name' => 'required',
                    'category_id' => 'required|exists:store.categories,id',
                    'price' => 'required',
                    'img' => 'nullable|array',
                    'img.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'status' => 'required|in:0,1',
                    'promotional_price' => 'nullable|numeric|min:0|lt:price',

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
                ]
            );
            DB::beginTransaction();
            $product = Product::findOrFail($id);
            $product->name = $request->name;
            $product->category_id = $request->category_id;
            $product->price = $request->price;
            $product->promotional_price = $request->promotional_price;
            $product->description = $request->description;
            $product->status = $request->status;
            if ($request->hasFile('img')) {
                foreach ($request->file('img') as $file) {
                     if(!$file->isValid()) {
                        throw new \Exception('Erro ao fazer upload da imagem.');
                    }
                    $fileName = $file->hashName();
                    $filePath = $file->storeAs('products',$fileName ,'public');
                    if (!$filePath) {
                        throw new \Exception('Falha ao salvar imagem.');
                    }
                    ProductImage::create([
                        'product_id' => $product->id,
                        'img' => $filePath
                    ]);
                }
            }
            $product->save();
            ProductVariation::where('product_id', $product->id)->delete();
            $variation_values = array_filter($request->variation_values ?? []);
            if($request->has_variation) {
                 if (!$request->variation_id) {
                    throw new \Exception('Selecione o tipo de variação.');
                }
                if (empty($variation_values)) {
                    throw new \Exception('Adicione pelo menos um valor de variação.');
                }
                foreach ($variation_values as $value) {
                    ProductVariation::create([
                        'product_id' => $product->id,
                        'variation_id' => $request->variation_id,
                        'value' => $value
                    ]);
                }
            }
            DB::commit();
            return back()->with('success', 'Produto atualizado com sucesso!');
        } catch (ValidationException $e){
            DB::rollBack();
            return back()->withErrors($e->validator)->withInput();
        } catch (QueryException $e){
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocorreu um erro na conexão com banco de dados.'])->withInput();
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.'])->withInput();
        }
    }
    public function destroy(string $id)
    {
        try {
            $product = Product::with('productImages')->findOrFail($id);
            foreach ($product->productImages as $image) {
                Storage::disk('public')->delete($image->img);
                $image->delete();
            }
            $product->delete();
            return redirect()->route('dashboard.products.index')->with('success', 'Produto excluído com sucesso!');
        } catch (ModelNotFoundException $e){
            return back()->withErrors(['error' => 'Produto não encontrado.']);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
        }
    }
    public function deleteImage($id)
    {
        try {
            $productImage = ProductImage::findOrFail($id);
            Storage::disk('public')->delete($productImage->img);
            $productImage->delete();
            return back()->with('success', 'Imagem excluída com sucesso.');
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Imagem não encontrada.']);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
        }
    }
}
