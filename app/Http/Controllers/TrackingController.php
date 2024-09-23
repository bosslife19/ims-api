<?php

namespace App\Http\Controllers;

use App\Exports\TrackingsExport;
use App\Models\Item;
use App\Models\NewItem;
use App\Models\School;
use App\Models\Tracking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Symfony\Component\HttpFoundation\JsonResponse;

class TrackingController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->user();
        if ($user['role']['slug'] === "head-teacher"){
            // find school
            $school = School::where(["school_id" => $user['school']])->first();
            $items = Tracking::where(['school_id' => $school['id'], "current_point" => 'school'])->latest()->get();
        } else {
            $items = Tracking::latest()->get();
        }
        return response()->json(['items' => $this->collection($items)], 200);
    }

    public function show(int $id): JsonResponse
    {
        $item = Tracking::where(['id' => $id])->first();
        if (is_null($item)) return response()->json(['message' => 'Tracking not found'], 422);
        return response()->json(['item' => $this->resource($item)], 200);
    }

    public function find_items(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            "search" => "string|required",
        ]);

        $searchParam = trim($validated['search']);
        $items = Item::where('item_name', 'LIKE', "%{$searchParam}%")->get();

        return response()->json(['count' => count($items), 'items' => $items], 200);
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

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            "item_id" => "numeric|required",
            "school_id" => "numeric|required",
            "priority" => "string|required",
            "action" => "string|required",
            "quantity" => "numeric|required",
            "reference_number" => "string|required",
            "additional_info" => "string|nullable",
            "date_moved" => "date|required",
        ]);
        // find item if exists
        $item = Item::where(['id' => $validated['item_id']])->first();
        if (is_null($item)) return response()->json(['message' => 'Item not found'], 422);
        // check stock of items
        if($item['quantity'] < $validated['quantity']) return response()->json(['message' => 'Not enough items in stock'], 422);
        // store tracking
        $tracking = new Tracking();
        $tracking->fill($validated);
        $tracking->save();
        // return response
        return response()->json(['item' => $tracking], 201);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $tracking = Tracking::where(['id' => $id])->first();
        if (is_null($tracking)) return response()->json(['message' => 'Tracking not found'], 422);
        $validated = $request->validate([
            "action" => "string|required",
        ]);
        $tracking->action = $validated['action'];
        if ($tracking['action'] === "In School") {
            $tracking->current_point = "school";
        }
        $tracking->save();
        return response()->json(['message' => 'Tracking updated successfully'], 200);
    }

    public function confirm_delivery(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
        if ($user['role']['slug'] !== "head-teacher") return response()->json(['message' => 'Unauthorized'], 403);
        $school = School::where(["school_id" => $user['school']])->first();
        $tracking = Tracking::where(['school_id' => $school['id'], "current_point" => 'school', "id" => $id])->first();
        // check delivery status
        if ($tracking['status'] === 'delivered') return response()->json(['message' => 'Tracking already confirmed'], 422);
        if (is_null($tracking)) return response()->json(['message' => 'Tracking not found'], 422);
        if ($tracking['action'] !== "In School" && $tracking['current_point'] !== "school") return response()->json(['message' => 'Item has not gotten to school'], 422);
        // update status of item tracking and update the item quantity in warehouse and add record to the school item db
        DB::beginTransaction();
        // tracking status
        $tracking->status = 'delivered';
        $tracking->save();

        // update item inventory
        $item = Item::where(['id' => $tracking['item_id']])->first();
        if (is_null($item)) return response()->json(['message' => 'Item not found'], 422);
        $item->quantity -= $tracking['quantity'];
        $item->save();

        // check if item is already in school and update quantity or create new item
        $find_item = NewItem::where(["item_code" => $item['item_code'], "school_id" => $school['school_id']])->first();
        if($find_item) {
            $find_item->quantity += $tracking['quantity'];
            $find_item->save();
        } else {
            // store item in the School Item DB
            $school_item = new NewItem();
            $school_item->item_code = $item["item_code"];
            $school_item->item_name = $item["item_name"];
            $school_item->subject_category = $item["subject_category"];
            $school_item->distribution = $item["distribution"];
            $school_item->quantity = $tracking["quantity"];
            $school_item->image = $item["image"];
            $school_item->save();
        }
        DB::commit();

        return response()->json(['message' => 'Item added to school'], 200);
    }

    public function exportRecords(Request $request)
    {
        $validated = $request->validate([
            'type' => "string|required|in:excel,pdf",
        ]);

        if ($validated['type'] === "excel") {
            return Excel::download(new TrackingsExport, 'trackings.xlsx');
        } else if ($validated['type'] === 'pdf') {
            $trackings = Tracking::with(['school', 'item'])->latest()->get();
            $pdf = PDF::loadView('trackings_pdf', compact('trackings'));
            return $pdf->download('trackings.pdf');
        }
    }

    public function filter_records(Request $request): JsonResponse
    {
        $value = $request->query("location");

        if ($value) {
            $items = Tracking::where(["current_point" => $value])->with(['item', 'school'])->latest()->get();
        } else {
            $items = Tracking::with(['school', 'item'])->with(['item', 'school'])->latest()->get();
        }

        return response()->json(["items" => $this->collection($items)], 200);
    }

    public function collection(Collection $collection): array
    {
        return $collection->transform(function ($model) {
            return $this->resource($model);
        })->toArray();
    }

    public function resource(Model $model): array
    {
        return [
            "id" => $model['id'],
            "item" => [
                "id" => $model['item']['id'],
                "name" => $model['item']['item_name'],
                "code" => $model['item']['item_code'],
            ],
            "school" => [
                "id" => $model['school']['id'],
                "name" => $model['school']['name'],
            ],
            "priority" => $model['priority'],
            "action" => $model['action'],
            "quantity" => $model['quantity'],
            "reference_number" => $model['reference_number'],
            "additional_info" => $model['additional_info'],
            "date_moved" => $model['date_moved'],
            "current_point" => $model['current_point'],
            "start_point" => $model['start_point'],
            "status" => $model['status'],
        ];
    }
}
