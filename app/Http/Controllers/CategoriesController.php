<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CategoriesController extends Controller
{
    public function index(): View
    {
        $categories = Category::paginate(10);
        return view('dashboard.categories.index', compact('categories'));
    }
    public function create(): View
    {
        return view('dashboard.categories.create');
    }
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate(
                [
                    'name' => 'required',
                    'img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'status' => 'required|in:0,1'
                ],
                [
                    'name.required' => 'O nome da categoria é obrigatório.',
                    'img.image' => 'O arquivo deve ser uma imagem.',
                    'img.mimes' => 'A imagem deve ser JPG, PNG ou WEBP.',
                    'img.max' => 'A imagem deve ter no máximo 2MB.',
                    'status.required' => 'O status é obrigatório.',
                    'status.in' => 'Selecione um status válido.'
                ]
            );
            $filePath = null;
            if ($request->hasFile('img')) {
                $fileName = $request->file('img')->hashName();
                $filePath = $request->file('img')->storeAs('categories', $fileName, 'public');
            }
            Category::create([
                'name' => $request->name,
                'img' => $filePath,
                'status' => $request->status,
            ]);
            return redirect()->route('dashboard.categories.index')->with('success', 'Categoria criada com sucesso!');
        } catch (ValidationException $e){
            return back()->withErrors($e->validator)->withInput();
        } catch (QueryException $e){
            return back()->withErrors(['error' => 'Ocorreu um erro na conexão com banco de dados.'])->withInput();
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.'])->withInput();
        }
    }
    public function edit(string $id): View
    {
        $category = Category::findOrFail($id);
        return view('dashboard.categories.edit' , compact('category'));
    }
    public function update(Request $request, string $id): RedirectResponse
    {
        try {
            $request->validate(
                [
                    'name' => 'required',
                    'img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'status' => 'required|in:0,1'
                ],
                [
                    'name.required' => 'O nome da categoria é obrigatório.',
                    'img.image' => 'O arquivo deve ser uma imagem.',
                    'img.mimes' => 'A imagem deve ser JPG, PNG ou WEBP.',
                    'img.max' => 'A imagem deve ter no máximo 2MB.',
                    'status.required' => 'O status é obrigatório.',
                    'status.in' => 'Selecione um status válido.'
                ]
            );
            $filePath = null;
            if ($request->hasFile('img')) {
                $fileName = $request->file('img')->hashName();
                $filePath = $request->file('img')->storeAs('categories', $fileName, 'public');
            }
            $category = Category::findOrFail($id);
            $category->name = $request->name;

            $oldStatus = $category->status;

            if ($oldStatus == 1 && $request->status == 0) {
                Product::where('category_id', $category->id)->update(['status' => 0]);
            }

            if($filePath){
                if($category->img){
                    Storage::disk('public')->delete($category->img);
                }
                $category->img = $filePath;
            }
            $category->status = $request->status;
            $category->save();
            return redirect()->route('dashboard.categories.index')->with('success', 'Categoria salva com sucesso!');
        } catch (ValidationException $e){
            return back()->withErrors($e->validator)->withInput();
        } catch (ModelNotFoundException $e){
            return back()->withErrors(['error' => 'Categoria não encontrada.'])->withInput();
        } catch (QueryException $e){
            return back()->withErrors(['error' => 'Ocorreu um erro na conexão com banco de dados.'])->withInput();
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.'])->withInput();
        }
    }
    public function destroy(string $id): RedirectResponse
    {
        try {
            $products = Product::where('category_id', $id)->exists();
            if($products){
                return back()->withErrors(['error' => 'Erro: Existe produtos na categoria.']);
            }
            $category = Category::findOrFail($id);
            if($category->img){
                Storage::disk('public')->delete($category->img);
            }
            $category->delete();
            return redirect()->route('dashboard.categories.index')->with('success', 'Categoria excluída com sucesso!');
        } catch (ModelNotFoundException $e){
            return back()->withErrors(['error' => 'Categoria não encontrada.']);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
        }
    }
}
