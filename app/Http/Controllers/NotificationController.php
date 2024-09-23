<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends Controller
{
    public function get_notifications(Request $request): JsonResponse
    {
        $notifications = Notification::where(["user_id" => $request->user()->id])->latest()->get();
        return response()->json(["notifications" => $notifications, "success" => true], Response::HTTP_OK);
    }

    public function get_notification(int $id): JsonResponse
    {
        $notification = Notification::where(["id" => $id, "user_id" => auth()->user()->id])->first();
        if(!$notification) return response()->json(["success" => false, "message" => "Notification not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        return response()->json(["success" => true, "notification" => $notification], Response::HTTP_OK);
    }

    public function create_notification(Request $request): JsonResponse
    {
        $validate = $this->validate($request, [
            "title" => "required|string",
            "body" => "required|string",
            "attachment" => "nullable|string",
            "target" => "required|string",
        ]);

        $users = User::where("id", "!=", auth()->user()->id)->get();
        foreach ($users as $user) {
            $notification = new Notification();
            $notification->user_id = $user->id;
            $notification->title = $validate['title'];
            $notification->body = $validate['body'];
            $notification->attachment = $validate['attachment'];
            $notification->save();
        }
        return response()->json(["success" => true, "message" => "Notification sent out successfully"], Response::HTTP_CREATED);
    }

    public function sendNotification(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'audience' =>'string|required'
        ]);

        if($request->audience =='QA'){
            try {
                $userEmails = User::whereHas('role', function ($query) {
                    $query->where('name', 'QA');
                })->pluck('email');


                $users = User::whereHas('role', function ($query) {
                    $query->where('name', 'QA')->get();
                });

                foreach($users as $user){
                    $notification = new Notification();
            $notification->user_id = $user->id;
            $notification->title = $request['title'];
            $notification->body = $request['message'];
            // $notification->attachment = $validate['attachment'];
            $notification->save();
                }


                foreach ($userEmails as $email) {
                    $mainUser = User::where('email', $email)->first();

                    $notificationHistory = new NotificationHistory();
                    $notificationHistory->create([
                        'title'=>$request->title,
                        'message'=>$request->message,
                        'user_id'=>$mainUser->id
                    ]);

                    // Send the email
                    Mail::send('emails.new-message', ['title' => $request->title, 'messages' => $request->message], function ($msg) use ($email) {
                        $msg->to($email);
                        $msg->subject('New Notification');
                    });



                }



                 return response()->json(['message'=>'Email sent succesfully']);
            } catch (\Exception $e) {
                // Handle errors

                return response()->json(['status' => 'Failed to send email', 'error' => $e->getMessage()], 500);
            }
        }
        else if($request->audience == 'Warehouse Staff'){
            try {
                $userEmails = User::whereHas('role', function ($query) {
                    $query->where('name', 'Warehouse Staff');
                })->pluck('email');

                $users = User::whereHas('role', function ($query) {
                    $query->where('name', 'Warehouse Staff');
                })->get();

                foreach($users as $user){
                    $notification = new Notification();
            $notification->user_id = $user->id;
            $notification->title = $request['title'];
            $notification->body = $request['message'];
            // $notification->attachment = $validate['attachment'];
            $notification->save();
                }

                foreach ($userEmails as $email) {
                    $mainUser = User::where('email', $email)->first();

                    $notificationHistory = new NotificationHistory();
                    $notificationHistory->create([
                        'title'=>$request->title,
                        'message'=>$request->message,
                        'user_id'=>$mainUser->id
                    ]);
                    // Send the email
                    Mail::send('emails.new-message', ['title' => $request->title, 'messages' => $request->message], function ($msg) use ($email) {
                        $msg->to($email);
                        $msg->subject('New Notification');
                    });


                }


                return response()->json(['message'=>'Email sent successfully']);
            } catch (\Exception $e) {
                // Handle errors

                return response()->json(['status' => 'Failed to send email', 'error' => $e->getMessage()], 500);
            }
        }

       else if($request->audience == 'Head Teacher'){
            try {
                $userEmails = User::whereHas('role', function ($query) {
                    $query->where('name', 'Head Teacher');
                })->pluck('email');

                $users = User::whereHas('role', function ($query) {
                    $query->where('name', 'Head Teacher');
                })->get();

                foreach($users as $user){
                    $notification = new Notification();
            $notification->user_id = $user->id;
            $notification->title = $request['title'];
            $notification->body = $request['message'];
            // $notification->attachment = $validate['attachment'];
            $notification->save();
                }


                foreach ($userEmails as $email) {
                    $mainUser = User::where('email', $email)->first();

                    $notificationHistory = new NotificationHistory();
                    $notificationHistory->create([
                        'title'=>$request->title,
                        'message'=>$request->message,
                        'user_id'=>$mainUser->id
                    ]);
                    // Send the email
                    Mail::send('emails.new-message', ['title' => $request->title, 'messages' => $request->message], function ($msg) use ($email) {
                        $msg->to($email);
                        $msg->subject('New Notification');
                    });

                }


                return response()->json(['message'=>'Message sent Successfully']);
            } catch (\Exception $e) {
                // Handle errors

                return response()->json(['status' => 'Failed to send email', 'error' => $e->getMessage()], 500);
            }
        }
        else if($request->audience == 'Admin'){
            try {
                // Debugging: Log the data being sent
                $userEmails = User::whereHas('role', function ($query) {
                    $query->where('name', 'Admin');
                })->pluck('email');

                $users = User::whereHas('role', function ($query) {
                    $query->where('name', 'Admin');
                })->get();

                foreach($users as $user){
                    $notification = new Notification();
                    $notification->user_id = $user->id;
                    $notification->title = $request['title'];
                    $notification->body = $request['message'];
                    // $notification->attachment = $validate['attachment'];
                    $notification->save();
                }

                // auth()->user()->message_count++;
                // auth()->user()->save();


                foreach ($userEmails as $email) {
                    $mainUser = User::where('email', $email)->first();

                    $notificationHistory = new NotificationHistory();
                    $notificationHistory->create([
                        'title'=>$request->title,
                        'message'=>$request->message,
                        'user_id'=>$mainUser->id
                    ]);
                    // Send the email
                    Mail::send('emails.new-message', ['title' => $request->title, 'messages' => $request->message], function ($msg) use ($email) {
                        $msg->to($email);
                        $msg->subject('New Notification');
                    });
                    foreach($users as $user){
                        $user->message_count++;
                        $user->save();
                    }


                }


                // return response()->json(['message'=>auth()->user()->message_count]);
                return response()->json(['message'=>'Email sent successfully']);
            } catch (\Exception $e) {
                // Handle errors

                return response()->json(['status' => 'Failed to send email', 'error' => $e->getMessage()], 500);
            }
        }




    }

}
