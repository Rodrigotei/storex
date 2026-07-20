<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Store;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    public function index()
    {
        try {
            $store = app('store');
            $store = Store::with('address')->where('slug', $store->slug)->first();
            $tenant_id = $store->id;
            $categories = Category::where('status', true)->where('tenant_id', $tenant_id)->limit(5)->get();
            $lastProducts = Product::with(['productImages', 'category'])->latest()->limit(10)->where('status', true)->where('tenant_id', $tenant_id)->get();
            $promotionalProducts = Product::with(['productImages', 'category'])->whereNotNull('promotional_price')->where('status', true)->where('tenant_id', $tenant_id)->limit(10)->get();

            return view('client.index', compact('store', 'categories', 'lastProducts', 'promotionalProducts'));
        } catch (\Throwable $th) {
            return view('client.error');
        }
    }

    public function categories()
    {
        try {
            $tenant_id = $this->getTenantId();
            $categories = Category::where('status', true)->where('tenant_id', $tenant_id)->get();
            if ($categories->isEmpty()) {
                return redirect()->route('client.home', ['tenant' => app('store')->slug])->withErrors(['error' => 'Nenhuma categoria foi encontrada.']);
            }

            return view('client.categories', compact('categories'));
        } catch (\Throwable $th) {
            return view('client.error');
        }
    }

    public function category(string $tenant, string $id) // tenant required by route binding
    {
        try {
            $tenant_id = $this->getTenantId();
            $products = Product::with(['category', 'productImages'])->where('category_id', $id)->where('status', true)->where('tenant_id', $tenant_id)->get();
            if ($products->isEmpty()) {
                return redirect()->route('client.home', ['tenant' => app('store')->slug])->withErrors(['error' => 'Nenhum produto encontrado para esta categoria.']);
            }
            $categoryName = Category::where('status', true)->where('tenant_id', $tenant_id)->find($id, 'name');

            return view('client.category', compact('categoryName', 'products'));
        } catch (\Throwable $th) {
            return view('client.error');
        }
    }

    public function product(string $tenant, string $id) // tenant required by route binding
    {
        try {
            $tenant_id = $this->getTenantId();
            $product = Product::with([
                'category',
                'productImages',
                'variationGroups',
                'variationGroups.variation',
                'variationGroups.productVariations' => function ($query) {
                    $query->where('status', true)->orderBy('value');
                },
            ])->where('status', true)->where('tenant_id', $tenant_id)->find($id);
            if (! $product) {
                return redirect()->route('client.home', ['tenant' => app('store')->slug])->withErrors(['error' => 'Produto não encontrado.']);
            }

            return view('client.product', compact('product'));
        } catch (\Throwable $th) {
            return view('client.error');
        }
    }

    public function cart()
    {
        try {
            $delivery_fee = $this->getStore()->delivery_fee;
            $cart = session($this->cartSessionKey(), []);

            return view('client.cart', compact('delivery_fee', 'cart'));
        } catch (\Throwable $th) {
            return view('client.error');
        }
    }

    public function add(Request $request)
    {
        $tenant_id = $this->getTenantId();

        try {
            $request->validate([
                'product_id' => [
                    'required',
                    Rule::exists('products', 'id')
                        ->where('tenant_id', $tenant_id)
                        ->where('status', true),
                ],
                'quantity' => 'required|integer|min:1|max:99',
                'observation' => 'nullable|string|max:500',
                'product_variations' => 'nullable|array',
                'product_variations.*' => 'integer|distinct',
            ]);

            $cart = session()->get($this->cartSessionKey(), []);
            $product = Product::with([
                'productImages',
                'variationGroups.variation',
                'variationGroups.productVariations',
            ])->where('status', true)->where('tenant_id', $tenant_id)->findOrFail($request->product_id);

            $selectedVariationIds = $request->product_variations ?? [];
            $selectedVariations = collect();
            if (! empty($selectedVariationIds)) {
                $selectedVariations = ProductVariation::with('variationGroup.variation')
                    ->whereIn('id', $selectedVariationIds)
                    ->where('product_id', $product->id)
                    ->where('status', true)
                    ->whereHas('variationGroup', function ($query) use ($tenant_id, $product) {
                        $query->where('tenant_id', $tenant_id)->where('product_id', $product->id);
                    })
                    ->get();
                if ($selectedVariations->count() !== count($selectedVariationIds)) {
                    return back()->withErrors(['error' => 'Variação inválida selecionada.'])->withInput();
                }
            }

            foreach ($product->variationGroups as $group) {
                $selectionCount = $selectedVariations->where('variation_group_id', $group->id)->count();

                if ($selectionCount < $group->min_selection || $selectionCount > $group->max_selection) {
                    return back()->withErrors([
                        'error' => "Selecione entre {$group->min_selection} e {$group->max_selection} opção(ões) em {$group->variation->name}.",
                    ])->withInput();
                }
            }

            $variations = [];
            $additionalPrice = 0;
            foreach ($selectedVariations as $variation) {
                $variations[] = [
                    'id' => $variation->id,
                    'group' => $variation->variationGroup->variation->name,
                    'value' => $variation->value,
                    'additional_price' => $variation->additional_price,
                ];
                $additionalPrice += $variation->additional_price;
            }

            $basePrice = $product->promotional_price ?? $product->price;
            $finalPrice = $basePrice + $additionalPrice;

            $variationKey = collect($selectedVariationIds)->sort()->implode('-');

            $observation = trim($request->observation);
            $found = false;
            foreach ($cart as &$item) {
                if ($item['product_id'] == $product->id && $item['variation_key'] == $variationKey && $item['observation'] == $observation) {
                    $item['qty'] += $request->quantity;
                    $found = true;
                    break;
                }
            }
            if (! $found) {
                $cart[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $basePrice,
                    // 'additional_price' => $additionalPrice,
                    'final_price' => $finalPrice,
                    'qty' => $request->quantity,
                    'variation' => $product->variationGroups->first()?->variation?->name,
                    'variation_key' => $variationKey,
                    'variations' => $variations,
                    'image' => $product->productImages->first()->img ?? null,
                    'observation' => $observation,
                ];
            }
            session()->put($this->cartSessionKey(), $cart);
            $totalQty = collect($cart)->sum('qty');
            session()->put($this->cartCountSessionKey(), $totalQty);

            return back()->with('success', 'Produto adicionado!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Produto não encontrado.']);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
        }
    }

    public function delete(Request $request)
    {
        try {
            $request->validate(['index' => 'required|integer|min:0']);
            $cart = session()->get($this->cartSessionKey(), []);
            if (empty($cart)) {
                return back()->withErrors(['error' => 'O carrinho está vazio.']);
            }
            if (! isset($cart[$request->index])) {
                return back()->withErrors(['error' => 'Item inválido.']);
            }
            unset($cart[$request->index]);
            $cart = array_values($cart);
            session()->put($this->cartSessionKey(), $cart);
            $totalQty = collect($cart)->sum('qty');
            session()->put($this->cartCountSessionKey(), $totalQty);

            return back()->with('success', 'Produto removido com sucesso.');
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
        }
    }

    public function orderFinish(Request $request)
    {
        try {
            $request->validate(
                [
                    'name' => ['required', 'string', 'max:100'],
                    'type' => ['required', 'in:delivery,pickup'],
                    'payment_method' => ['required', 'in:pix,cash,card'],
                    'address' => ['required_if:type,delivery', 'nullable', 'string', 'max:255'],
                    'number' => ['required_if:type,delivery', 'nullable', 'string', 'max:20'],
                    'neighborhood' => ['required_if:type,delivery', 'nullable', 'string', 'max:100'],
                    'complement' => ['nullable', 'string', 'max:255'],
                    'change_for' => ['nullable', 'numeric', 'min:0', 'prohibited_unless:payment_method,cash'],
                ],
                [
                    'name.required' => 'Informe seu nome',
                    'type.required' => 'Selecione entrega ou retirada',
                    'payment_method.required' => 'Selecione a forma de pagamento',
                    'address.required_if' => 'Informe o endereço para entrega',
                    'number.required_if' => 'Informe o número',
                    'neighborhood.required_if' => 'Informe o bairro',
                ]
            );
            $tenant_id = $this->getTenantId();
            $cart = session($this->cartSessionKey(), []);
            if (empty($cart)) {
                return back()->withErrors(['error' => 'Seu carrinho está vazio']);
            }

            $productIds = collect($cart)->pluck('product_id')->unique()->values();
            $products = Product::with('variationGroups.variation')
                ->where('tenant_id', $tenant_id)
                ->where('status', true)
                ->whereIn('id', $productIds)
                ->get()
                ->keyBy('id');

            $checkoutItems = [];
            $orderSubtotal = 0;
            foreach ($cart as $item) {
                $product = $products->get($item['product_id']);
                if (! $product) {
                    return back()->withErrors(['error' => 'O produto '.$item['name'].' não está mais disponível.']);
                }

                $quantity = filter_var($item['qty'] ?? null, FILTER_VALIDATE_INT, [
                    'options' => ['min_range' => 1, 'max_range' => 99],
                ]);
                if ($quantity === false) {
                    return back()->withErrors(['error' => 'A quantidade de um item do carrinho é inválida.']);
                }

                $selectedVariationIds = collect($item['variations'] ?? [])->pluck('id')->filter()->unique()->values();
                $selectedVariations = ProductVariation::with('variationGroup.variation')
                    ->whereIn('id', $selectedVariationIds)
                    ->where('product_id', $product->id)
                    ->where('status', true)
                    ->whereHas('variationGroup', function ($query) use ($tenant_id, $product) {
                        $query->where('tenant_id', $tenant_id)->where('product_id', $product->id);
                    })
                    ->get();

                if ($selectedVariations->count() !== $selectedVariationIds->count()) {
                    return back()->withErrors(['error' => 'Uma opção do carrinho não está mais disponível. Revise o produto.']);
                }

                foreach ($product->variationGroups as $group) {
                    $selectionCount = $selectedVariations->where('variation_group_id', $group->id)->count();
                    if ($selectionCount < $group->min_selection || $selectionCount > $group->max_selection) {
                        return back()->withErrors(['error' => 'As opções de '.$product->name.' precisam ser selecionadas novamente.']);
                    }
                }

                $basePrice = (float) ($product->promotional_price ?? $product->price);
                $variations = $selectedVariations->map(fn ($variation) => [
                    'id' => $variation->id,
                    'group' => $variation->variationGroup->variation->name,
                    'value' => $variation->value,
                    'additional_price' => (float) $variation->additional_price,
                ])->values()->all();
                $finalPrice = $basePrice + $selectedVariations->sum('additional_price');
                $itemSubtotal = $finalPrice * $quantity;

                $checkoutItems[] = array_merge($item, [
                    'name' => $product->name,
                    'price' => $basePrice,
                    'final_price' => $finalPrice,
                    'qty' => $quantity,
                    'variations' => $variations,
                ]);
                $orderSubtotal += $itemSubtotal;
            }

            $delivery_fee = $request->type === 'delivery' ? (float) (app('store')->delivery_fee ?? 0) : 0;
            $total = $orderSubtotal + $delivery_fee;

            if ($request->payment_method === 'cash' && $request->filled('change_for') && (float) $request->change_for < $total) {
                return back()->withErrors(['change_for' => 'O valor para troco deve ser igual ou maior que o total do pedido.'])->withInput();
            }

            $paymentMap = [
                'pix' => 'PIX',
                'cash' => 'Dinheiro',
                'card' => 'Cartão',
            ];
            $typeMap = [
                'delivery' => 'Entrega',
                'pickup' => 'Retirada',
            ];
            $message = "*NOVO PEDIDO*\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";
            foreach ($checkoutItems as $item) {
                $itemSubtotal = $item['final_price'] * $item['qty'];
                $message .= " *{$item['name']}*\n";
                $message .= "   {$item['qty']}x R$ ".number_format($item['price'], 2, ',', '.')."\n";
                foreach (collect($item['variations'])->groupBy('group') as $groupName => $variationValues) {
                    $message .= "   {$groupName}:\n";
                    foreach ($variationValues as $variationValue) {
                        $additionalPrice = number_format($variationValue['additional_price'], 2, ',', '.');
                        $message .= "      ↳ {$variationValue['value']} (+ R$ {$additionalPrice})\n";
                    }
                }
                if (! empty($item['observation'])) {
                    $message .= "   Obs: {$item['observation']}\n";
                }
                $message .= '   Subtotal: R$ '.number_format($itemSubtotal, 2, ',', '.')."\n\n";
            }
            $message .= "━━━━━━━━━━━━━━━━━━━━━━\n";
            $message .= "*RESUMO*\n";
            $message .= 'Subtotal: R$ '.number_format($orderSubtotal, 2, ',', '.')."\n";
            if ($request->type === 'delivery') {
                $message .= 'Entrega: '.($delivery_fee > 0 ? 'R$ '.number_format($delivery_fee, 2, ',', '.') : 'Grátis')."\n";
            }
            $message .= '*Total: R$ '.number_format($total, 2, ',', '.')."*\n\n";
            $message .= "*CLIENTE*\n";
            $message .= "Nome: {$request->name}\n";
            $message .= "*{$typeMap[$request->type]}*\n";
            if ($request->type === 'delivery') {
                $message .= "{$request->address}, {$request->number}\n";
                $message .= "{$request->neighborhood}\n";
                if (! empty($request->complement)) {
                    $message .= "Complemento: {$request->complement}\n";
                }
                $message .= "\n";
            }
            $message .= '*PAGAMENTO*: ';
            $message .= $paymentMap[$request->payment_method]."\n";
            if ($request->payment_method === 'cash' && ! empty($request->change_for)) {
                $message .= "Troco para: R$ {$request->change_for}\n";
            }
            $store = $this->getStore();
            $url = $store->whatsappUrl($message);
            if (! $url) {
                return back()->withErrors(['error' => 'O WhatsApp da loja não está configurado corretamente.']);
            }

            session()->put($this->cartSessionKey(), $checkoutItems);

            return redirect()->away($url);
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro ao finalizar seu pedido.'])->withInput();
        }
    }

    public function search(Request $request)
    {
        $tenant_id = $this->getTenantId();
        try {
            $request->validate(
                [
                    'search' => 'required',
                ],
            );
            $search = $request->search;
            $products = Product::with(['category', 'productImages'])->whereLike('name', '%'.$search.'%')->where('status', true)->where('tenant_id', $tenant_id)->get();
            if ($products->isEmpty()) {
                return redirect()->route('client.home', ['tenant' => app('store')->slug])->withErrors(['error' => 'Nada foi encontrado.']);
            }

            return view('client.search', compact('search', 'products'));
        } catch (ValidationException $e) {
            return back()->withErrors(['error' => 'Nada encontrado.']);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Ocorreu um erro inesperado.']);
        }
    }

    private function getTenantId()
    {
        return $this->getStore()->id;
    }

    private function getStore()
    {
        $store = app('store');

        if (! $store) {
            abort(403, 'Store não definida');
        }

        return $store;
    }

    private function cartSessionKey(): string
    {
        return 'cart:'.$this->getTenantId();
    }

    private function cartCountSessionKey(): string
    {
        return 'cart_count:'.$this->getTenantId();
    }
}
