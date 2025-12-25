<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Promo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/products')->with('error', 'Keranjang kosong');
        }

        return view('checkout.index', compact('cart'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'name'      => 'required|string',
            'phone'     => 'required|string',
            'address'   => 'required|string',
            'city'      => 'required|string',
            'post_code' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/products');
        }

        // ================= HITUNG TOTAL =================
        $total = collect($cart)->sum('subtotal');

        // ================= PROMO =================
        $discount = 0;
        $promoId  = null;

        if (session()->has('promo')) {
            $promo    = session('promo');
            $discount = min($promo['discount'], $total);
            $promoId  = $promo['id'];
        }

        $finalTotal = $total - $discount;

        // ================= TRANSACTION =================
        $order = DB::transaction(function () use (
            $cart,
            $request,
            $finalTotal,
            $discount,
            $promoId
        ) {
            // SIMPAN ORDER
            $order = Order::create([
                'order_code'      => 'ORD-' . Str::upper(Str::random(8)),
                'name'            => $request->name,
                'phone'           => $request->phone,
                'address'         => $request->address,
                'city'            => $request->city,
                'post_code'       => $request->post_code,
                'total_price'     => $finalTotal,
                'discount_amount' => $discount,
                'promo_id'        => $promoId,
                'status'          => 'pending',
                'user_id'         => auth()->id(),
            ]);

            // SIMPAN ORDER ITEMS + KURANGI STOK
            foreach ($cart as $item) {
    $qty = (int) ($item['quantity'] ?? 0);

            if ($qty <= 0) {
        throw new \Exception('Quantity tidak valid');
    }

                $product = Product::lockForUpdate()->findOrFail($item['product_id']);


                // 🚨 CEK STOK
                if ($product->stock < $qty) {
        throw new \Exception("Stok {$product->name} tidak mencukupi");
    }
                OrderItem::create([
        'order_id'   => $order->id,
        'product_id' => $product->id,
        'quantity'   => $qty,
        'price'      => $item['price'],
        'subtotal'   => $item['subtotal'],
    ]);

                // 🔻 KURANGI STOK (INI YANG SEKARANG AKAN BEKERJA)
                $product->decrement('stock', $qty);
}

            return $order;
        });

        // ================= BERSIHKAN SESSION =================
        session()->forget(['cart', 'promo']);

        return redirect()->route('checkout.success', $order->id);
    }

    public function success($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('checkout.success', compact('order'));
    }

    public function applyPromo(Request $request)
    {
        $request->validate([
            'promo_code' => 'required',
        ]);

        $promo = Promo::where('code', $request->promo_code)
            ->where('is_active', true)
            ->first();

        if (! $promo) {
            return redirect()->back()
                ->with('promo_error', 'Kode promo tidak valid');
        }

        session()->put('promo', [
            'id'       => $promo->id,
            'code'     => $promo->code,
            'discount' => $promo->discount_amount,
        ]);

        return redirect()->back()
            ->with('promo_success', 'Promo berhasil digunakan');
    }
}
