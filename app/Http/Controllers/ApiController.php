<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index($endpoint, $resourceName)
    {
        $resources = Resource::where('endpoint_id', $endpoint)
                             ->where('resource_name', $resourceName)
                             ->get();
                             
        $mapped = $resources->map(function ($item) {
            $data = $item->data ?? [];
            $data['_id'] = $item->id;
            return $data;
        });

        return response()->json($mapped);
    }

    public function show($endpoint, $resourceName, $id)
    {
        $resource = Resource::where('endpoint_id', $endpoint)
                            ->where('resource_name', $resourceName)
                            ->where('id', $id)
                            ->first();

        if (!$resource) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        $data = $resource->data ?? [];
        $data['_id'] = $resource->id;

        return response()->json($data);
    }

    public function store(Request $request, $endpoint, $resourceName)
    {
        // Accept any JSON body
        $data = $request->all();
        
        $resource = Resource::create([
            'endpoint_id' => $endpoint,
            'resource_name' => $resourceName,
            'data' => $data,
        ]);

        $responseData = $resource->data ?? [];
        $responseData['_id'] = $resource->id;

        return response()->json($responseData, 201);
    }

    public function update(Request $request, $endpoint, $resourceName, $id)
    {
        $resource = Resource::where('endpoint_id', $endpoint)
                            ->where('resource_name', $resourceName)
                            ->where('id', $id)
                            ->first();

        if (!$resource) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        $resource->update([
            'data' => $request->all(),
        ]);

        return response()->json(['message' => 'Resource updated successfully'], 200);
    }

    public function destroy($endpoint, $resourceName, $id)
    {
        $resource = Resource::where('endpoint_id', $endpoint)
                            ->where('resource_name', $resourceName)
                            ->where('id', $id)
                            ->first();

        if (!$resource) {
            return response()->json(['error' => 'Resource not found'], 404);
        }

        $resource->delete();

        return response()->json(null, 204);
    }
}
