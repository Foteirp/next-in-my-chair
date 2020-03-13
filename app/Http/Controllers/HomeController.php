<?php

namespace App\Http\Controllers;

use App\Category;
use App\Shop;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $shops = Shop::orderBy('created_at', 'desc')->with(['categories', 'days'])
            ->searchResults()
            ->paginate(3);

        $mapShops = $shops->makeHidden(['active', 'created_at', 'updated_at', 'deleted_at', 'created_by_id', 'photos', 'media']);
        $latitude = $shops->count() && (request()->filled('category') || request()->filled('search')) ? $shops->average('latitude') : 36.778259;
        $longitude = $shops->count() && (request()->filled('category') || request()->filled('search')) ? $shops->average('longitude') : -119.417931;

        return view('home', compact('categories', 'shops', 'mapShops', 'latitude', 'longitude'));
    }

    public function show(Shop $shop)
    {
        $shop->load(['categories', 'days']);

        return view('shop', compact('shop'));
    }
}
