<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
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
        return view('dashboard.products.create', compact('categories'));
    }
    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'name' => 'required',
                    'category_id' => 'required|exists:store.categories,id',
                    'price' => 'required',
                    'img' => 'nullable|array',
                    'img.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'status' => 'required|in:0,1'
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
                    'status.in' => 'Selecione um status válido.'
                ]
            );
            $product = Product::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'price' => $request->price,
                'description' => $request->description,
                'status' => $request->status
            ]);
            if ($request->hasFile('img')) {
                foreach ($request->file('img') as $file) {
                    $fileName = $file->hashName();
                    $filePath = $file->storeAs('products',$fileName ,'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'img' => $filePath
                    ]);
                }
            }
            return redirect()->route('dashboard.products.index')->with('success', 'Produto criado com sucesso!');
        } catch (ValidationException $e){
            return back()->withErrors($e->validator)->withInput();
        } catch (QueryException $e){
            return back()->withErrors(['error' => 'Ocorreu um erro na conexão com banco de dados.'])->withInput();
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.'])->withInput();
        }
    }
    public function edit(string $id)
    {
         try {
            $categories = Category::all();
            $product = Product::with('productImages')->findOrFail($id);
            return view('dashboard.products.edit',compact('product', 'categories'));
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
                    'status' => 'required|in:0,1'
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
                    'status.in' => 'Selecione um status válido.'
                ]
            );
            $product = Product::findOrFail($id);
            $product->name = $request->name;
            $product->category_id = $request->category_id;
            $product->price = $request->price;
            $product->description = $request->description;
            $product->status = $request->status;
            if ($request->hasFile('img')) {
                foreach ($request->file('img') as $file) {
                    $fileName = $file->hashName();
                    $filePath = $file->storeAs('products',$fileName ,'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'img' => $filePath
                    ]);
                }
            }
            $product->save();
            return back()->with('success', 'Produto atualizado com sucesso!');
        } catch (ValidationException $e){
            return back()->withErrors($e->validator)->withInput();
        } catch (QueryException $e){
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        } catch (\Throwable $th) {
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
