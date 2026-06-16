<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LaravelGenerator;
use App\Services\GoGenerator;

class GenerateController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'resource_name' => 'required|string',
            'fields' => 'required|array',
            'fields.*.name' => 'required|string',
            'fields.*.type' => 'required|string',
            'fields.*.is_nullable' => 'boolean',
            'fields.*.is_primary' => 'boolean',
        ]);

        $schema = $request->all();

        $laravelGenerator = new LaravelGenerator($schema);
        $goGenerator = new GoGenerator($schema);

        return response()->json([
            'laravel' => $laravelGenerator->generate(),
            'go' => $goGenerator->generate(),
        ]);
    }
}
