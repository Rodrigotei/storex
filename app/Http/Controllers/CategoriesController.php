<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CategoriesController extends Controller
{
    public function index(): View
    {
        try {
            $this->authorize('viewAny', Category::class);
            $categories = Category::where('tenant_id', auth()->user()->store->id)->paginate(10);

            return view('dashboard.categories.index', compact('categories'));
        } catch (\Throwable $th) {
            return view('dashboard.error');
        }
    }

    public function create(): View
    {
        try {
            $this->authorize('create', Category::class);

            return view('dashboard.categories.create');
        } catch (\Throwable $th) {
            return view('dashboard.error');
        }
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $this->authorize('create', Category::class);
            $request->validate(
                [
                    'name' => 'required',
                    'img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'status' => 'required|in:0,1',
                ],
                [
                    'name.required' => 'O nome da categoria é obrigatório.',
                    'img.image' => 'O arquivo deve ser uma imagem.',
                    'img.mimes' => 'A imagem deve ser JPG, PNG ou WEBP.',
                    'img.max' => 'A imagem deve ter no máximo 2MB.',
                    'status.required' => 'O status é obrigatório.',
                    'status.in' => 'Selecione um status válido.',
                ]
            );
            $filePath = null;
            if ($request->hasFile('img')) {
                $fileName = $request->file('img')->hashName();
                $filePath = $request->file('img')->storeAs('categories', $fileName);
            }
            DB::beginTransaction();
            Category::create([
                'tenant_id' => auth()->user()->store->id,
                'name' => $request->name,
                'img' => $filePath,
                'status' => $request->status,
            ]);
            DB::commit();

            return redirect()->route('dashboard.categories.index')->with('success', 'Categoria criada com sucesso!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (QueryException $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Ocorreu um erro na conexão com banco de dados.'])->withInput();
        } catch (\Throwable $th) {
            DB::rollBack();
            if (! empty($filePath)) {
                Storage::delete($filePath);
            }

            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.'])->withInput();
        }
    }

    public function edit(string $id): View|RedirectResponse
    {
        try {
            $category = Category::where('tenant_id', auth()->user()->store->id)->findOrFail($id);
            $this->authorize('update', $category);

            return view('dashboard.categories.edit', compact('category'));
        } catch (ModelNotFoundException $e) {
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
                    'status' => 'required|in:0,1',
                ],
                [
                    'name.required' => 'O nome da categoria é obrigatório.',
                    'img.image' => 'O arquivo deve ser uma imagem.',
                    'img.mimes' => 'A imagem deve ser JPG, PNG ou WEBP.',
                    'img.max' => 'A imagem deve ter no máximo 2MB.',
                    'status.required' => 'O status é obrigatório.',
                    'status.in' => 'Selecione um status válido.',
                ]
            );
            $category = Category::where('tenant_id', auth()->user()->store->id)->findOrFail($id);
            $this->authorize('update', $category);
            $oldImage = $category->img;
            $newImage = null;
            if ($request->hasFile('img')) {
                $fileName = $request->file('img')->hashName();
                $newImage = $request->file('img')->storeAs('categories', $fileName);
            }
            DB::beginTransaction();
            $category->name = $request->name;
            $oldStatus = $category->status;
            if ($oldStatus == 1 && $request->status == 0) {
                Product::where('category_id', $category->id)->where('tenant_id', auth()->user()->store->id)->update(['status' => 0]);
            }
            $category->status = $request->status;
            if ($newImage) {
                $category->img = $newImage;
            }
            $category->save();
            DB::commit();
            if ($newImage && $oldImage) {
                Storage::delete($oldImage);
            }

            return redirect()->route('dashboard.categories.index')->with('success', 'Categoria salva com sucesso!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Categoria não encontrada.'])->withInput();
        } catch (QueryException $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Ocorreu um erro na conexão com banco de dados.'])->withInput();
        } catch (\Throwable $th) {
            DB::rollBack();
            if (! empty($newImage)) {
                Storage::delete($newImage);
            }

            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.'])->withInput();
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $products = Product::where('category_id', $id)->where('tenant_id', auth()->user()->store->id)->exists();
            if ($products) {
                return back()->withErrors(['error' => 'Existem produtos vinculados à essa categoria.']);
            }
            $category = Category::where('tenant_id', auth()->user()->store->id)->findOrFail($id);
            $this->authorize('delete', $category);
            DB::beginTransaction();
            $imgPath = $category->img;
            $category->delete();
            DB::commit();
            if ($imgPath) {
                Storage::delete($imgPath);
            }

            return redirect()->route('dashboard.categories.index')->with('success', 'Categoria excluída com sucesso!');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Categoria não encontrada.']);
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
        }
    }
}
