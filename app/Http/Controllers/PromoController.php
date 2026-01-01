<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Validasi kode promo (AJAX)
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $promo = Promo::findByCode($request->code);

        if (!$promo) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode promo tidak valid atau sudah tidak aktif.',
            ], 422);
        }

        $discount = $promo->discount;
        $subtotal = $request->subtotal;

        // Pastikan diskon tidak lebih besar dari subtotal
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        $total = $subtotal - $discount;

        return response()->json([
            'valid' => true,
            'message' => 'Kode promo berhasil diterapkan!',
            'promo' => [
                'code' => $promo->code,
                'discount' => $discount,
                'formatted_discount' => 'Rp ' . number_format($discount, 0, ',', '.'),
            ],
            'total' => $total,
            'formatted_total' => 'Rp ' . number_format($total, 0, ',', '.'),
        ]);
    }
}
