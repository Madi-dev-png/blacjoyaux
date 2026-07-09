<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function index()
    {
        $promoCodes = PromoCode::latest()->get();

        return view('admin.promo-codes.index', compact('promoCodes'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['code'] = strtoupper($data['code']);

        PromoCode::create($data);

        return back()->with('success', 'Code promo créé.');
    }

    public function update(Request $request, PromoCode $promoCode)
    {
        $data = $this->validated($request, $promoCode);
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $promoCode->update($data);

        return back()->with('success', 'Code promo mis à jour.');
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();

        return back()->with('success', 'Code promo supprimé.');
    }

    protected function validated(Request $request, ?PromoCode $promoCode = null): array
    {
        return $request->validate([
            'code' => 'required|string|max:40|unique:promo_codes,code'.($promoCode ? ','.$promoCode->id : ''),
            'type' => 'required|in:percent,fixed',
            'value' => 'required|integer|min:1',
            'min_subtotal' => 'nullable|integer|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);
    }
}
