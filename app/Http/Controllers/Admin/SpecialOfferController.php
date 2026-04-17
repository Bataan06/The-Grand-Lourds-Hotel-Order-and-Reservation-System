<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SpecialOffer;
use Illuminate\Http\Request;

class SpecialOfferController extends Controller
{
    public function index()
    {
        $offers = SpecialOffer::latest()->get();
        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        return view('admin.offers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'badge'       => 'required|string|max:50',
            'description' => 'required|string',
            'highlight'   => 'required|string|max:255',
        ]);

        SpecialOffer::create([
            'title'           => $request->title,
            'badge'           => $request->badge,
            'emoji'           => $request->emoji ?? '🎁',
            'description'     => $request->description,
            'highlight'       => $request->highlight,
            'gradient'        => $request->gradient        ?? 'linear-gradient(135deg,#4a0080,#7b2ff7)',
            'highlight_bg'    => $request->highlight_bg    ?? '#f0e6ff',
            'highlight_color' => $request->highlight_color ?? '#4a0080',
            'is_active'       => true,
        ]);

        return redirect()->route('admin.offers.index')
            ->with('success', 'Special offer added successfully!');
    }

    public function edit(SpecialOffer $offer)
    {
        return view('admin.offers.edit', compact('offer'));
    }

    public function update(Request $request, SpecialOffer $offer)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'badge'       => 'required|string|max:50',
            'description' => 'required|string',
            'highlight'   => 'required|string|max:255',
        ]);

        $offer->update([
            'title'           => $request->title,
            'badge'           => $request->badge,
            'emoji'           => $request->emoji ?? $offer->emoji ?? '🎁',
            'description'     => $request->description,
            'highlight'       => $request->highlight,
            'gradient'        => $request->gradient        ?? $offer->gradient        ?? 'linear-gradient(135deg,#4a0080,#7b2ff7)',
            'highlight_bg'    => $request->highlight_bg    ?? $offer->highlight_bg    ?? '#f0e6ff',
            'highlight_color' => $request->highlight_color ?? $offer->highlight_color ?? '#4a0080',
        ]);

        return redirect()->route('admin.offers.index')
            ->with('success', 'Special offer updated!');
    }

    public function destroy(SpecialOffer $offer)
    {
        $offer->delete();
        return redirect()->route('admin.offers.index')
            ->with('success', 'Special offer deleted!');
    }

    public function toggle(SpecialOffer $offer)
    {
        $offer->update(['is_active' => !$offer->is_active]);
        return back()->with('success', 'Offer status updated!');
    }
}