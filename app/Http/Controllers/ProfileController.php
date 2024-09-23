<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProfileController extends Controller
{
    public function get_profile(): JsonResponse
    {
        $user = auth()->user();
        return response()->json(["user" => $user, "success" => true]);
    }

    public function update_profile(Request $request): JsonResponse
    {
        $user = auth()->user();
        $validated = $request->validate([
            'name' => 'required|string',
            'phone_number' => 'required|string',
            'image' => 'nullable|string',
        ]);

        $user->name = $validated['name'];
        $user->phone_number = $validated['phone_number'];
        $user->image = $validated['image'];
        $user->save();

        return response()->json(["user" => $user, "success" => true]);
    }
}
