<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceImage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ServicesController extends Controller
{
    public function index()
    {
        try {
            $tenant_id = auth()->user()->id;
            $services = Service::with(['serviceImages'])->where('tenant_id', $tenant_id)->paginate(10);
            return view('dashboard.services.index', compact('services'));
        } catch (\Throwable $th) {
            return view('dashboard.error');
        }
    }
    public function create()
    {
        try {
            return view('dashboard.services.create');
        } catch (\Throwable $th) {
            return view('dashboard.error');
        }
    }
    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'name' => 'required',
                    'price' => 'required|numeric|min:0',
                    'img' => 'nullable|array',
                    'img.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'status' => 'required|in:0,1',
                ],
                [
                    'name.required' => 'O nome é obrigatório.',
                    'price.required' => 'O preço é obrigatório',
                    'img.*.image' => 'O arquivo deve ser uma imagem.',
                    'img.*.mimes' => 'A imagem deve ser JPG, PNG ou WEBP.',
                    'img.*.max' => 'Cada imagem deve ter no máximo 2MB.',
                    'status.required' => 'O status é obrigatório.',
                    'status.in' => 'Selecione um status válido.',
                    
                ]
            );
            DB::beginTransaction();
            $service = Service::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'status' => $request->status,
                'duration' => $request->duration ?? null,
                'tenant_id' => auth()->user()->id
            ]);
            if ($request->hasFile('img')) {
                foreach ($request->file('img') as $file) {
                    if(!$file->isValid()) {
                        throw new \Exception('Erro ao fazer upload da imagem.');
                    }
                    $fileName = $file->hashName();
                    $filePath = $file->storeAs('services',$fileName ,'public');
                    if (!$filePath) {
                        throw new \Exception('Falha ao salvar imagem.');
                    }
                    ServiceImage::create([
                        'tenant_id' => auth()->user()->id,
                        'service_id' => $service->id,
                        'img' => $filePath
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('dashboard.services.index')->with('success', 'Serviço criado com sucesso!');
        } catch (ValidationException $e){
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
            $service = Service::with(['serviceImages'])->where('tenant_id', auth()->user()->id)->findOrFail($id);
            return view('dashboard.services.edit',compact('service'));
        } catch (ModelNotFoundException $e){
            return back()->withErrors(['error' => 'Serviço não encontrado.']);
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
                    'price' => 'required',
                    'img' => 'nullable|array',
                    'img.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                    'status' => 'required|in:0,1',
                    'promotional_price' => 'nullable|numeric|min:0|lt:price',

                ],
                [
                    'name.required' => 'O nome é obrigatório.',
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
            $service = Service::findOrFail($id);
            DB::beginTransaction();
            $service->name = $request->name;
            $service->price = $request->price;
            $service->promotional_price = $request->promotional_price;
            $service->description = $request->description;
            $service->status = $request->status;
            if ($request->hasFile('img')) {
                foreach ($request->file('img') as $file) {
                     if(!$file->isValid()) {
                        throw new \Exception('Erro ao fazer upload da imagem.');
                    }
                    $fileName = $file->hashName();
                    $filePath = $file->storeAs('services',$fileName ,'public');
                    if (!$filePath) {
                        throw new \Exception('Falha ao salvar imagem.');
                    }
                    ServiceImage::create([
                        'tenant_id' => auth()->user()->store->id,
                        'service_id' => $service->id,
                        'img' => $filePath
                    ]);
                }
            }
            $service->save();
            DB::commit();
            return back()->with('success', 'Serviço atualizado com sucesso!');
        } catch (ValidationException $e){
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
            $service = Service::with('serviceImages')->where('tenant_id', auth()->user()->id)->findOrFail($id);
            DB::beginTransaction();
            $imgPaths = $service->serviceImages->pluck('img')->toArray();
            $service->delete();
            DB::commit();
            if(!empty($imgPaths)){
                foreach ($imgPaths as $image) {
                    Storage::disk('public')->delete($image);
                }
            }   
            return redirect()->route('dashboard.services.index')->with('success', 'Serviço excluído com sucesso!');
        } catch (ModelNotFoundException $e){
            return back()->withErrors(['error' => 'Serviço não encontrado.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
        }
    }
    public function deleteImage(string $id)
    {
        try {
            $serviceImage = ServiceImage::where('tenant_id', auth()->user()->id)->findOrFail($id);
            DB::beginTransaction();
            $imgPath = $serviceImage->img;
            $serviceImage->delete();
            DB::commit();
            Storage::disk('public')->delete($imgPath);
            return back()->with('success', 'Imagem excluída com sucesso.');
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Imagem não encontrada.']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
        }
    }
}
