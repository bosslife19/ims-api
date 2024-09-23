<?php

namespace App\Http\Controllers;

use App\Models\NotificationHistory as ModelsNotificationHistory;
use Illuminate\Http\Request;

class NotificationHistory extends Controller
{
    public function getHistory (Request $request){
        $user = $request->user();

        $history = ModelsNotificationHistory::where('user_id', $user->id)->get();

        return response($history, 200);
    }
}
