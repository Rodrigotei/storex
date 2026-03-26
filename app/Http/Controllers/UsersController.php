<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(string $id)
    {
        try {
            $user = User::with(['store', 'store.address'])->findOrFail($id);
            return view('dashboard.profile.edit', compact('user'));
        } catch (ModelNotFoundException $e){
            return back()->withErrors(['error' => 'Usuário não encontrado.']);
        } 
        catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $request->validate(
                [
                    'name' => 'required|string|max:255',
                    'document' => 'required|string|max:20',
                    'email' => 'required|email|max:255|unique:users,email,' . $id,
                    'store.name' => 'required|string|max:255',
                    'store.phone' => 'required|string|max:20',
                    'address.street' => 'required|string|max:255',
                    'address.number' => 'required|string|max:20',
                    'address.neighborhood' => 'required|string|max:100',
                    'address.city' => 'required|string|max:100',
                    'address.state' => 'required|string|max:50',
                    'address.zip_code' => 'required|string|size:8', // sem traço
                    'password' => 'nullable|string|min:6|confirmed',
                ],
                [
                    'name.required' => 'O nome é obrigatório.',
                    'name.string' => 'O nome deve ser uma string válida.',
                    'name.max' => 'O nome não pode ter mais de 255 caracteres.',
                    'email.required' => 'O e-mail é obrigatório.',
                    'email.email' => 'Informe um e-mail válido.',
                    'email.max' => 'O e-mail não pode ter mais de 255 caracteres.',
                    'email.unique' => 'Este e-mail já está em uso.',
                    'document.required' => 'O CPF/CNPJ é obrigatório.',
                    'document.string' => 'O CPF/CNPJ deve ser válido.',
                    'document.max' => 'O CPF/CNPJ não pode ter mais de 20 caracteres.',
                    'store.name.required' => 'O nome da loja é obrigatório.',
                    'store.name.string' => 'O nome da loja deve ser uma string válida.',
                    'store.name.max' => 'O nome da loja não pode ter mais de 255 caracteres.',
                    'store.phone.string' => 'O telefone da loja deve ser válido.',
                    'store.phone.required' => 'O telefone da loja é obrigatório.',
                    'store.phone.max' => 'O telefone da loja não pode ter mais de 20 caracteres.',
                    'address.street.required' => 'O nome da rua é obrigatório.',
                    'address.street.string' => 'O nome da rua deve ser válido.',
                    'address.street.max' => 'O nome da rua não pode ter mais de 255 caracteres.',
                    'address.number.required' => 'O número é obrigatório.',
                    'address.number.string' => 'O número deve ser válido.',
                    'address.number.max' => 'O número não pode ter mais de 20 caracteres.',
                    'address.neighborhood.required' => 'O bairro é obrigatório.',
                    'address.neighborhood.string' => 'O bairro deve ser válido.',
                    'address.neighborhood.max' => 'O bairro não pode ter mais de 100 caracteres.',
                    'address.city.required' => 'A cidade é obrigatória.',
                    'address.city.string' => 'A cidade deve ser válida.',
                    'address.city.max' => 'A cidade não pode ter mais de 100 caracteres.',
                    'address.state.required' => 'O estado é obrigatório.',
                    'address.state.string' => 'O estado deve ser válido.',
                    'address.state.max' => 'O estado não pode ter mais de 50 caracteres.',
                    'address.zip_code.required' => 'O CEP é obrigatório.',
                    'address.zip_code.string' => 'O CEP deve ser válido.',
                    'address.zip_code.size' => 'O CEP deve conter exatamente 8 números.',
                    'password.string' => 'A senha deve ser uma string válida.',
                    'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
                    'password.confirmed' => 'As senhas não coincidem.',
                ]
            );

            $user = User::with(['store', 'store.address'])->findOrFail($id);
            
            DB::beginTransaction();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->document = $request->document;

            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
            $user->save();
            $storeData = $request->store;
            $store = $user->store ?? $user->store()->create([]);

            $filePath = null;
            if($request->hasFile('img')){
                $fileName = $request->file('img')->hashName();
                $filePath = $request->file('img')->storeAs('logo', $fileName, 'public');

            }

            $store->update([
                'name' => $storeData['name'],
                'slug' => str_replace(' ', '-', strtolower($storeData['name'])),
                'phone' => $storeData['phone'],
                'description' => $storeData['description'],
                'delivery_fee' => $storeData['delivery_fee'],
                'img' => $filePath
            ]);

            $addressData = $request->address;
            $address = $store->address ?? $store->address()->create([]);
            $address->update([
                'street' => $addressData['street'],
                'number' => $addressData['number'],
                'neighborhood' => $addressData['neighborhood'],
                'city' => $addressData['city'],
                'state' => $addressData['state'],
                'zip_code' => $addressData['zip_code'],
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Perfil atualizado com sucesso!');
        } catch (ValidationException $e){
            return back()->withErrors($e->validator)->withInput();
        } catch (ModelNotFoundException $e){
            DB::rollBack();
            return back()->withErrors(['error' => 'Usuário não encontrado'])->withInput();
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
        //
    }
}
