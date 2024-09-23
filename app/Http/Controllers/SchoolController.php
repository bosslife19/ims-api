<?php

namespace App\Http\Controllers;

use App\Models\AllSchools;
use App\Models\School;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SchoolController extends Controller
{
    public function index(): JsonResponse
    {
        $schools = AllSchools::paginate(50);
        return response()->json([
            "schools" => $schools->items(),
            'count'=> $schools->total(),
            "message" => "Fetched schools",
            "pagination" => [
                "total" => $schools->total(),
                "per_page" => $schools->perPage(),
                "current_page" => $schools->currentPage(),
                "last_page" => $schools->lastPage(),
                "next_page_url" => $schools->nextPageUrl(),
                "prev_page_url" => $schools->previousPageUrl(),
            ]
        ], Response::HTTP_OK);
    }

    public function allSchools(): JsonResponse
    {
        $schools = AllSchools::all();
        return response()->json([
            "schools" => $schools,
        ]);
    }

    public function UploadSchools(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('file');
        $handle = fopen($file, "r");
        $header = true;

        while (($row = fgetcsv($handle, 1000, ",")) !== false) {
            if($header) {
                $header = false;
                continue;
            }
            School::create([
                'name' => $row[0],
                'school_id' => $row[1],
            ]);
        }
        fclose($handle);
        return response()->json(["message" => "Upload success"], Response::HTTP_OK);
    }

    public function show(int $id): JsonResponse
    {
        $school = School::where(["id" => $id])->firstOrFail();
        if (!$school) return response()->json(["message" => "School not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        // get school items
        $items = $school->items($school->school_id);
        return response()->json([
            "message" => "Fetched school",
            "school" => $school,
            "items" => $items->items(),
            "count" => $items->total(),
            "pagination" => [
                "total" => $items->total(),
                "per_page" => $items->perPage(),
                "current_page" => $items->currentPage(),
                "last_page" => $items->lastPage(),
                "next_page_url" => $items->nextPageUrl(),
                "prev_page_url" => $items->previousPageUrl(),
            ]
        ], Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "name" => "required|string",
            "website" => "nullable|string|url",
            "email" => "required|string|email",
            "phone_number" => "required|string",
            "level" => "required|string",
            "logo" => "nullable|string",
            "address" => "required|string",
            "city" => "required|string",
            "lga" => "required|string",
            "postal_code" => "required|string",
        ]);

        // create school record
        $school = School::create($validated);
        return response()->json(["school" => $school, "message" => "School created!"], Response::HTTP_CREATED);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            "name" => "required|string",
            "website" => "nullable|string|url",
            "email" => "required|string|email",
            "phone_number" => "required|string",
            "level" => "required|string",
            "logo" => "nullable|string",
            "address" => "required|string",
            "city" => "required|string",
            "lga" => "required|string",
            "postal_code" => "required|string",
        ]);

        $school = School::where(["id" => $id])->firstOrFail();
        if (!$school) return response()->json(["message" => "School not found"], Response::HTTP_UNPROCESSABLE_ENTITY);

        $school->update($validated);
        return response()->json(["school" => $school, "message" => "School updated!"], Response::HTTP_OK);
    }

    public function destroy(int $id): JsonResponse
    {
        $school = School::where(["id" => $id])->firstOrFail();
        if (!$school) return response()->json(["message" => "School not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        $school->delete();
        return response()->json(["message" => "School deleted!"], Response::HTTP_OK);
    }

    public function find_schools(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            "search" => "string|required",
        ]);

        $searchParam = trim($validated['search']);
        $schools = School::where('name', 'LIKE', "%{$searchParam}%")
            ->orWhere('school_id', 'LIKE', "%{$searchParam}%")->get();

        return response()->json(["count" => count($schools), "schools" => $schools], 200);
    }
}
