<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Validasi kode promo (AJAX)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validate(Request $request)
    {
        // Validasi input
        $request->validate([
            'code' => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
        ]);

        // Cari promo berdasarkan code (case-insensitive)
        $promo = Promo::where('code', strtoupper($request->code))
                    ->where('is_active', true)
                    ->first();

        // Jika promo tidak ditemukan atau tidak aktif
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
            'promo' => [
                'id' => $promo->id,
                'code' => $promo->code,
                'discount' => $discount,
                'formatted_discount' => 'Rp ' . number_format($discount, 0, ',', '.'),
            ],
            'subtotal' => $subtotal,
            'total' => $total,
            'formatted_total' => 'Rp ' . number_format($total, 0, ',', '.'),
        ]);
    }

    /**
     * Get all active promo codes 
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActivePromos()
    {
        $promos = Promo::where('is_active', true)
                    ->select('code', 'discount')
                    ->get()
                    ->map(function ($promo) {
                        return [
                            'code' => $promo->code,
                            'discount' => $promo->discount,
                            'formatted_discount' => 'Rp ' . number_format($promo->discount, 0, ',', '.'),
                        ];
                    });

        return response()->json([
            'success' => true,
            'promos' => $promos,
        ]);
    }
}