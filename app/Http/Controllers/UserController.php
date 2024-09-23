<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::latest()->get();
        return response()->json(["users" => $users, "message" => "Users fetched"], Response::HTTP_OK);
    }
    public function create(): JsonResponse
    {
        $roles = Role::latest()->get();
        return response()->json(["roles" => $roles], Response::HTTP_OK);
    }
    public function show(int $id): JsonResponse
    {
        $user = User::where(["id" => $id])->firstOrFail();
        if(!$user) return response()->json(["message" => "User not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        return response()->json(["user" => $user, "message" => "Fetched user"], Response::HTTP_OK);
    }
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "name" => "required|string",
            "username" => "required|string",
            "email" => "required|string",
            "phone_number" => "required|string",
            "level" => "required|string",
            "image" => "nullable|string",
            "role" => "required|string",
            "school" => "required_if:role,head-teacher|string|nullable",
            "department" => "required|string",
        ]);

        // find role
        $role = Role::where(["slug" => $validated["role"]])->firstOrFail();
        if (!$role) return response()->json(["message" => "Role not found"], Response::HTTP_UNPROCESSABLE_ENTITY);

        // create user
        $user = User::create([
            "role_id" => $role->id,
            "name" => $validated["name"],
            "username" => $validated["username"],
            "oracle_id" => $this->GenerateOracleID(),
            "email" => $validated["email"],
            "phone_number" => $validated["phone_number"],
            "level" => $validated["level"],
            "image" => $validated["image"],
            "department" => $validated["department"],
            "password" => Hash::make("password@1234"),
            "school" => $validated["school"],
        ]);
        if ($validated['role'] === "head-teacher") {
            $user->school_id = $this->GenerateSchoolID();
            $user->save();
        }
        return response()->json(["message" => "User created", "user" => $user], Response::HTTP_CREATED);
    }
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            "name" => "required|string",
            "username" => "required|string",
            "oracle_id" => "required|string",
            "email" => "required|string",
            "phone_number" => "required|string",
            "level" => "required|string",
            "image" => "nullable|string",
            "role" => "required|string",
            "school" => "required_if:role,head-teacher|string|nullable",
            "department" => "required|string",
        ]);

        $user = User::where(["id" => $id])->firstOrFail();
        if(!$user) return response()->json(["message" => "User not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        // find role
        $role = Role::where(["slug" => $validated["role"]])->firstOrFail();
        if (!$role) return response()->json(["message" => "Role not found"], Response::HTTP_UNPROCESSABLE_ENTITY);

        // update status
        $user = $user->update([
            "role_id" => $role['id'],
            "name" => $validated["name"],
            "username" => $validated["username"],
            "oracle_id" => $validated["oracle_id"],
            "email" => $validated["email"],
            "phone_number" => $validated["phone_number"],
            "level" => $validated["level"],
            "image" => $validated["image"],
            "department" => $validated["department"],
            "school" => $validated["school"],
        ]);
        return response()->json(["message" => "User updated", "user" => $user], Response::HTTP_OK);
    }
    public function updateStatus(int $id): JsonResponse
    {
        $user = User::where(["id" => $id])->firstOrFail();
        if(!$user) return response()->json(["message" => "User not found"], Response::HTTP_UNPROCESSABLE_ENTITY);

        $user->status = $user->status == 'active' ? 'inactive' : 'active';
        $user->save();
        return response()->json(["message" => "User status updated", "user" => $user], Response::HTTP_OK);
    }
    public function uploadUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $file = $request->file('file');
        $filePath = $file->getRealPath();
        $fileHandle = fopen($filePath, 'r');

        $header = fgetcsv($fileHandle, 0, ',');

        while (($row = fgetcsv($fileHandle, 0, ',')) !== FALSE) {
            $data = array_combine($header, $row);

            // Insert user data into the database
            User::create([
                'id' => $data['id'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'role_id'=>$data['role_id'],
                'name'=>$data['name'],
                'phone_number'=>$data['phone_number'],
                'level'=>$data['level'],
                'department'=>$data['department'],
                'username'=>$data['username'],
                'oracle_id'=>$data['oracle_id'],
                'status'=>$data['status'],
                'message_count'=>$data['message_count'],

                'email_verified_at'=>$data['email_verified_at'],
                'created_at'=>$data['created_at'],
                'updated_at'=>$data['updated_at'],

            ]);
        }

        fclose($fileHandle);

        return response()->json(['success' => 'File uploaded and data inserted successfully']);
    }
    private function GenerateSchoolID(): string
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $school_id = substr(str_shuffle(str_repeat($pool, 8)), 0, 8);

        $school_id_exists = User::where("school_id", $school_id)->first();
        if ($school_id_exists) {
            $this->GenerateTrx_Ref();
        } else {
            return $school_id;
        }
    }
    private function GenerateOracleID(): string
    {
        $pool = '0123456789';
        $nonZeroPool = '123456789';

        // Generate the first character from non-zero pool
        $firstChar = $nonZeroPool[random_int(0, strlen($nonZeroPool) - 1)];

        // Generate the remaining characters from the full pool
        $remainingChars = '';
        for ($i = 0; $i < 8 - 1; $i++) {
            $remainingChars .= $pool[random_int(0, strlen($pool) - 1)];
        }

        // Combine the first character with the remaining characters
        $oracle_id = $firstChar . $remainingChars;

        $oracle_id_exists = User::where("oracle_id", $oracle_id)->first();
        if ($oracle_id_exists) {
            $this->GenerateOracleID();
        } else {
            return $oracle_id;
        }
    }
}
