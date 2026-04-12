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
        try {
            $categories = Category::where('tenant_id', auth()->user()->store->id)->paginate(10);
            return view('dashboard.categories.index', compact('categories'));
        } catch (\Throwable $th) {
            return view('dashboard.error');
        }
    }
    public function create(): View
    {
        try {
            return view('dashboard.categories.create');
        } catch (\Throwable $th) {
            return view('dashboard.error');
        }
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
                'tenant_id' => auth()->user()->store->id,
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
    public function edit(string $id): View|RedirectResponse
    {
        try {
            $category = Category::findOrFail($id);
            return view('dashboard.categories.edit' , compact('category'));
        } catch (ModelNotFoundException $e){
            return back()->withErrors(['error' => 'Categoria não encontrada.']);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
        }
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
                return back()->withErrors(['error' => 'Existem produtos vinculados à essa categoria.']);
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
