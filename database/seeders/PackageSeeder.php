<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Package;
use App\Models\Event;
use App\Models\Venue;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Package::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $birthday = Event::where('name', 'like', '%Birthday%')->first();
        $wedding  = Event::where('name', 'like', '%Wedding%')->first();
        $greenAcres    = Venue::where('name', 'Green Acres')->first();
        $grandBallroom = Venue::where('name', 'Grand Ballroom')->first();

        $soupChoices    = ['Cream of Mushroom','Cream of Crab Meat','Sweet Corn Soup','Pumpkin Soup','Nido Soup with Quail Egg'];
        $dessertChoices = ['Fruit Salad','Buko Pandan Salad','Almond Lychee Jelly','Coffee Jelly','Butchi (Classic, Ube, Cheese, or Lotus Peanut Filling)'];
        $drinkChoices   = ['Glass of Coke','Glass of Iced Tea','Glass of Blue Lemonade','Glass of Cucumber Juice','Glass of Pink Lemonade'];

        // ── Price tiers structure: [price => [set => [items]]] ──
        $priceTiers570 = [
            570 => [
                'A' => ['Choice of Soup','Honey Buttered Chicken','Fish Fillet Ala King','Fry Mixed Vegetable','Unlimited Steamed Rice','Choice of Dessert','Choice of Drink'],
                'B' => ['Choice of Soup','Chicken Galantina','Fish Fillet w/ Tausi Sauce','Chinese Mixed Vegetables','Unlimited Steamed Rice','Choice of Dessert','Choice of Drink'],
                'C' => ['Choice of Soup','Honey Orange Chicken','Fish Fillet w/ Tartar Sauce','Special Chopsuey w/ Quail Egg','Unlimited Steamed Rice','Choice of Dessert','Choice of Drink'],
                'D' => ['Choice of Soup','Sate Chicken','Fish Fillet w/ Sweet & Sour Sauce','Vegetable Au Gratin w/ Quail Egg','Unlimited Steamed Rice','Choice of Dessert','Choice of Drink'],
            ],
            630 => [
                'A' => ['Choice of Soup','Chicken Galantina','Fish Fillet Ala King','Korean Beef Stew','Buttered Vegetables','Unlimited Steamed Rice','Choice of Dessert','Choice of Drink'],
                'B' => ['Choice of Soup','Fish Fillet Ala King','Chicken Ala King','Beef Pastel','Fry Mixed Vegetable','Unlimited Steamed Rice','Choice of Dessert','Choice of Drink'],
                'C' => ['Choice of Soup','Honey Buttered Chicken','Fish Fillet w/ Sweet & Sour','Pork Spicy Spareribs','Vegetable Au Gratin w/ Quail Egg','Unlimited Steamed Rice','Choice of Dessert','Choice of Drink'],
                'D' => ['Choice of Soup','Chicken Cordon Bleu','Fish Fillet w/ Tartar Sauce','Special Chopsuey w/ Quail Egg','Unlimited Steamed Rice','Choice of Dessert','Choice of Drink'],
            ],
            730 => [
                'A' => ['Choice of Soup','Chicken Ala King','Fish Fillet w/ Sweet & Sour Sauce','Roast Beef w/ Mushroom Sauce','Pork Spring Roll','Chinese Mixed Vegetables','Unlimited Steamed Rice','Choice of Dessert','Choice of Drink'],
                'B' => ['Choice of Soup','Sate Chicken','Fish Fillet Ala King','Pork Char Siu','Beef Pastel','Buttered Vegetables','Unlimited Steamed Rice','Choice of Dessert','Choice of Drink'],
                'C' => ['Choice of Soup','Honey Buttered Chicken','Shrimp Tempura','Korean Beef Stew','Pork w/ Chili Garlic Sauce','Special Chopsuey w/ Quail Egg','Unlimited Steamed Rice','Choice of Dessert','Choice of Drink'],
                'D' => ['Choice of Soup','Honey Orange Chicken','Fish Fillet w/ Tartar Sauce','Pineapple Teriyaki Pork','Beef Broccoli','Fry Mixed Vegetable','Unlimited Steamed Rice','Choice of Dessert','Choice of Drink'],
            ],
        ];

        // Build price_tiers JSON
        $buildTiers = function($tiers) use ($soupChoices, $dessertChoices, $drinkChoices) {
            $result = [];
            foreach ($tiers as $price => $sets) {
                $result[$price] = [];
                foreach ($sets as $set => $items) {
                    $result[$price][$set] = [
                        'items'           => $items,
                        'soup_choices'    => $soupChoices,
                        'dessert_choices' => $dessertChoices,
                        'drink_choices'   => $drinkChoices,
                    ];
                }
            }
            return json_encode($result);
        };

        $birthdayAmenities50 = ['Standard Physical Arrangement','Standard Centerpiece for all round tables','Table Numbers per table','Free Backdrop Name of Celebrant','Skirted tables for cakes & gifts','Free use of Venue for 4 hours','Free Food tasting for 2 persons','LCD Projector with roll up screen','Sound System with wireless microphone','Free Party Poppers','Led Lights & Red or Green Carpet','Well trained & Uniformed Banquet Staff'];
        $birthdayAmenities100 = ['Free 1 Room with Breakfast','Standard Physical Arrangement','Standard Centerpiece for all round tables','Table Numbers per Table','Free Backdrop Name of Celebrant','Free 2 Pieces Cake','Skirted Tables for the Cakes & Gift','Free Use of Venue for 4 Hours','Free Food Tasting for 2 Persons','LCD Projector with roll up screen','Sound System with wireless microphone','Free Party Poppers','Led Lights & Red or Green Carpet','Well trained & Uniformed Banquet Staff'];
        $birthdayAmenities150 = ['Free 1 Room with Breakfast','Standard Physical Arrangement','Standard Centerpiece for all round tables','Table Numbers per Table','Free Backdrop Name of Celebrant','Free 3 Pieces Cake','Skirted Tables for the Cakes & Gift','Free Use of Venue for 4 Hours','Free Food Tasting for 2 Persons','LCD Projector with roll up screen','Sound System with wireless microphone','Free Party Poppers','Led Lights & Red or Green Carpet','Well trained & Uniformed Banquet Staff','Free Professional Host/Emcee'];
        $birthdayAmenities200 = ['Free 2 Rooms with Breakfast','Standard Physical Arrangement','Standard Centerpiece for all round tables','Table Numbers per Table','Free Backdrop Name of Celebrant','Free 3 Pieces Birthday Cake','Skirted Tables for the Cakes & Gift','Free Use of Venue for 4 Hours','Free Food Tasting for 2 Persons','LCD Projector with roll up screen','Sound System with wireless microphone','Free Party Poppers','Led Lights & Red or Green Carpet','Well trained & Uniformed Banquet Staff','Free Professional Host/Emcee'];

        $weddingAmenities100 = ['Free 1 Room with Breakfast','Standard Physical Arrangement','Standard Centerpiece for all round tables','Table Numbers per Table','Free Backdrop Name of Couple','Free 2 Pieces Cake','Free 1 Bottle of Wine','Skirted Tables for the Cakes & Gift','Free Use of Venue for 4 Hours','Free Food Tasting for 2 Persons','LCD Projector with roll up screen','Sound System with wireless microphone','Free Party Poppers','Led Lights & Red or Green Carpet','Free Bridal Car with Flower','Well trained & Uniformed Banquet Staff'];
        $weddingAmenities150 = ['Free 1 Room with Breakfast','Standard Physical Arrangement','Standard Centerpiece for all round tables','Table Numbers per Table','Free Backdrop Name of Couple','Free 3 Pieces Cake','Free 1 Bottle of Wine','Skirted Tables for the Cakes & Gift','Free Use of Venue for 4 Hours','Free Food Tasting for 2 Persons','LCD Projector with roll up screen','Sound System with wireless microphone','Free Party Poppers','Led Lights & Red or Green Carpet','Free Bridal Car with Flower','Well trained & Uniformed Banquet Staff','Free Professional Host/Emcee'];
        $weddingAmenities200 = ['Free 2 Rooms with Breakfast','Standard Physical Arrangement','Standard Centerpiece for all round tables','Table Numbers per Table','Free Backdrop Name of Couple','Free 3 Pieces Cake','Free 1 Bottle of Wine','Skirted Tables for the Cakes & Gift','Free Use of Venue for 4 Hours','Free Food Tasting for 2 Persons','LCD Projector with roll up screen','Sound System with wireless microphone','Free Party Poppers','Led Lights & Red or Green Carpet','Free Bridal Car with Flower','Well trained & Uniformed Banquet Staff','Free Professional Host/Emcee','Free Wedding Live Singer','Free Entourage Van'];

        $packages = [
            ['event_id'=>$birthday->id,'venue_id'=>$greenAcres->id,'pax_range'=>'50-90','pax_min'=>50,'pax_max'=>90,'amenities'=>json_encode($birthdayAmenities50),'price_tiers'=>$buildTiers($priceTiers570),'is_active'=>true],
            ['event_id'=>$birthday->id,'venue_id'=>$grandBallroom->id,'pax_range'=>'100','pax_min'=>100,'pax_max'=>100,'amenities'=>json_encode($birthdayAmenities100),'price_tiers'=>$buildTiers($priceTiers570),'is_active'=>true],
            ['event_id'=>$birthday->id,'venue_id'=>$grandBallroom->id,'pax_range'=>'150','pax_min'=>150,'pax_max'=>150,'amenities'=>json_encode($birthdayAmenities150),'price_tiers'=>$buildTiers($priceTiers570),'is_active'=>true],
            ['event_id'=>$birthday->id,'venue_id'=>$grandBallroom->id,'pax_range'=>'200-300','pax_min'=>200,'pax_max'=>300,'amenities'=>json_encode($birthdayAmenities200),'price_tiers'=>$buildTiers($priceTiers570),'is_active'=>true],
            ['event_id'=>$wedding->id,'venue_id'=>$grandBallroom->id,'pax_range'=>'100','pax_min'=>100,'pax_max'=>100,'amenities'=>json_encode($weddingAmenities100),'price_tiers'=>$buildTiers($priceTiers570),'is_active'=>true],
            ['event_id'=>$wedding->id,'venue_id'=>$grandBallroom->id,'pax_range'=>'150','pax_min'=>150,'pax_max'=>150,'amenities'=>json_encode($weddingAmenities150),'price_tiers'=>$buildTiers($priceTiers570),'is_active'=>true],
            ['event_id'=>$wedding->id,'venue_id'=>$grandBallroom->id,'pax_range'=>'200-300','pax_min'=>200,'pax_max'=>300,'amenities'=>json_encode($weddingAmenities200),'price_tiers'=>$buildTiers($priceTiers570),'is_active'=>true],
        ];

        foreach ($packages as $pkg) {
            Package::create($pkg);
        }
    }
}