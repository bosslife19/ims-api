<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\JsonResponse;

class LocationController extends Controller
{
    public function index(): JsonResponse
    {
        $locations = Location::all();
        return response()->json([
            "message" => "Fetched locations successfully",
            "data" => $locations
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $location = Location::where("id", $id)->first();
        if (!$location) return response()->json(["message" => "Location not found"], 422);
        return response()->json([
            "message" => "Fetched location successfully",
            "data" => $location
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validate = $this->validate($request, [
            "title" => "required|string",
            "description" => "nullable|string",
        ]);

        $location = new Location();
        $location->title = $validate["title"];
        $location->description = $validate["description"];
        $location->slug = Str::slug($validate['title']);
        $location->save();

        return response()->json([
            "message" => "Location created successfully",
            "data" => $location
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validate = $this->validate($request, [
            "title" => "required|string",
            "description" => "nullable|string",
        ]);
        $location = Location::where("id", $id)->first();
        if (!$location) return response()->json(["message" => "Location not found"], 422);

        $location->title = $validate["title"];
        $location->description = $validate["description"];
        $location->slug = Str::slug($validate['title']);
        $location->save();

        return response()->json([
            "message" => "Location updated successfully",
            "data" => $location
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $location = Location::where("id", $id)->first();
        if (!$location) return response()->json(["message" => "Location not found"], 422);
        $location->delete();
        return response()->json([
            "message" => "Location deleted successfully"
        ]);
    }
}
