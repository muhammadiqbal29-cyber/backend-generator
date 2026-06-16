<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
});

Route::get('/', function () {
    $endpointId = bin2hex(random_bytes(16));
    return view('welcome', compact('endpointId'));
})->middleware('setLocale');
