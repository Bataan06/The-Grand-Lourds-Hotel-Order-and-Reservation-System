<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Event;
use App\Models\Venue;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    public function create($eventId)
    {
        $event  = Event::findOrFail($eventId);
        $venues = Venue::where('is_active', true)->get();
        return view('admin.packages.create', compact('event', 'venues'));
    }

    public function store(Request $request, $eventId)
    {
        $request->validate([
            'venue_id'  => 'required|exists:venues,id',
            'pax_min'   => 'required|integer|min:1',
            'pax_max'   => 'required|integer|gte:pax_min',
        ]);

        $event = Event::findOrFail($eventId);

        // Build price tiers from form input
        $priceTiers = $this->buildPriceTiers($request);

        // Build amenities
        $amenities = array_filter($request->input('amenities', []));

        Package::create([
            'event_id'    => $event->id,
            'venue_id'    => $request->venue_id,
            'pax_range'   => $request->pax_min . '–' . $request->pax_max,
            'pax_min'     => $request->pax_min,
            'pax_max'     => $request->pax_max,
            'amenities'   => json_encode(array_values($amenities)),
            'price_tiers' => json_encode($priceTiers),
            'is_active'   => $request->has('is_active'),
        ]);

        ActivityLog::log('package_created', Auth::user()->name . " added a package to {$event->name}", 'Packages');

        return redirect()->route('admin.events.packages', $eventId)
            ->with('success', 'Package added successfully!');
    }

    public function edit($eventId, $packageId)
    {
        $event   = Event::findOrFail($eventId);
        $package = Package::findOrFail($packageId);
        $venues  = Venue::where('is_active', true)->get();
        return view('admin.packages.edit', compact('event', 'package', 'venues'));
    }

    public function update(Request $request, $eventId, $packageId)
    {
        $request->validate([
            'venue_id' => 'required|exists:venues,id',
            'pax_min'  => 'required|integer|min:1',
            'pax_max'  => 'required|integer|gte:pax_min',
        ]);

        $package   = Package::findOrFail($packageId);
        $priceTiers = $this->buildPriceTiers($request);
        $amenities  = array_filter($request->input('amenities', []));

        $package->update([
            'venue_id'    => $request->venue_id,
            'pax_range'   => $request->pax_min . '–' . $request->pax_max,
            'pax_min'     => $request->pax_min,
            'pax_max'     => $request->pax_max,
            'amenities'   => json_encode(array_values($amenities)),
            'price_tiers' => json_encode($priceTiers),
            'is_active'   => $request->has('is_active'),
        ]);

        ActivityLog::log('package_updated', Auth::user()->name . " updated package #{$packageId}", 'Packages');

        return redirect()->route('admin.events.packages', $eventId)
            ->with('success', 'Package updated successfully!');
    }

    public function destroy($eventId, $packageId)
    {
        Package::findOrFail($packageId)->delete();
        ActivityLog::log('package_deleted', Auth::user()->name . " deleted package #{$packageId}", 'Packages');
        return redirect()->route('admin.events.packages', $eventId)
            ->with('success', 'Package deleted!');
    }

    public function packages($eventId)
    {
        $event    = Event::findOrFail($eventId);
        $packages = Package::with('venue')->where('event_id', $eventId)->orderBy('pax_min')->get();
        return view('admin.packages.index', compact('event', 'packages'));
    }

    public function toggle($eventId, $packageId)
    {
        $package = Package::findOrFail($packageId);
        $package->update(['is_active' => !$package->is_active]);
        return back()->with('success', 'Package status updated!');
    }

    private function buildPriceTiers(Request $request): array
    {
        $tiers  = [];
        $prices = $request->input('prices', []);
        $soups    = ['Cream of Mushroom', 'Pumpkin Soup', 'Sweet Corn with Crab Meat', 'Nido Soup with Quail Egg'];
        $desserts = ['Fruit Salad', 'Buko Pandan Salad', 'Coffee Jelly', 'Almond Lychee Jelly', 'Butchi (Classic, Ube, Cheese, or Lotus Peanut Filling)'];
        $drinks   = ['Glass of Coke', 'Glass of Iced Tea', 'Glass of Cucumber Juice', 'Glass of Blue Lemonade', 'Glass of Pink Lemonade'];

        foreach ($prices as $price) {
            $price = (int) $price;
            if (!$price) continue;
            $sets = [];
            foreach (['A', 'B', 'C', 'D'] as $set) {
                $items = $request->input("sets.{$price}.{$set}", []);
                $items = array_filter($items);
                if (!empty($items)) {
                    $sets[$set] = [
                        'items'           => array_values($items),
                        'soup_choices'    => $soups,
                        'dessert_choices' => $desserts,
                        'drink_choices'   => $drinks,
                    ];
                }
            }
            if (!empty($sets)) {
                $tiers[$price] = $sets;
            }
        }
        return $tiers;
    }
}