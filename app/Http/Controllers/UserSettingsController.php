<?php

namespace App\Http\Controllers;

use App\Models\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserSettingsController extends Controller
{
    public function get_settings(): JsonResponse
    {
        $user_settings = UserSettings::where(['user_id' => auth()->user()->id])->first();
        return response()->json(["settings" => $user_settings, "success" => true], 200);
    }

    public function update_settings(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "language" => "required|string",
            "timezone" => "required|string",
            "date_format" => "required|string",
            "email_notification" => "required|boolean",
            "dark_mode" => "required|boolean",
        ]);

        $user_settings = UserSettings::where(['user_id' => auth()->user()->id])->first();
        $user_settings->language = $validated["language"];
        $user_settings->timezone = $validated["timezone"];
        $user_settings->date_format = $validated["date_format"];
        $user_settings->email_notification = $validated["email_notification"];
        $user_settings->dark_mode = $validated["dark_mode"];
        $user_settings->save();

        return response()->json(["settings" => $user_settings, "success" => true], 200);
    }
}
