<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendScheduledEmail;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;


class EmailController extends Controller
{
    public function scheduleEmail(Request $request)
    {
        $request->validate([
            //  'audience' => 'required|exists:roles,id',
            'title' => 'required|string',
            'message' => 'required|string',
            'sendAt' => 'required|date_format:Y-m-d\TH:i'
        ]);
        

       
        $role = Role::where('name', $request['audience'])->first();
        $users = User::where('role_id', $role->id)->get();

        $emailDetails = [
            'subject' => $request->title,
            'message' => $request->message,
            'send_at' => $request->sendAt,
        ];
        foreach ($users as $user) {
            $delay = Carbon::parse($emailDetails['send_at'])->diffInSeconds(now());
            SendScheduledEmail::dispatch([
                'recipient' => $user->email,
                'subject' => $emailDetails['subject'],
                'message' => $emailDetails['message']
            ])->delay(now()->addSeconds($delay));
        }

        return response()->json(['message' => 'Emails scheduled successfully!'], 200);
    }
}
