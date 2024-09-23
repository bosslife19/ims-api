<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\School;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ItemRequestController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->user();
        $itemRequests = ItemRequest::where(['user_id' => $user['id']])->latest()->with(['school', 'item', 'user'])->get();
        return response()->json(["itemRequests" => $this->collection($itemRequests)], Response::HTTP_OK);
    }

    public function show(int $id): JsonResponse
    {
        $user = auth()->user();
        $itemRequest = ItemRequest::where(["id" => $id, 'user_id' => $user['id']])->with(['school', 'item', 'user'])->first();
        if (!$itemRequest) return response()->json(["message" => "Item Request not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        return response()->json(["itemRequest" => $this->resource($itemRequest)], Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        // get authenticated user
        $user = auth()->user();
        $school = School::where(["school_id" => $user['school']])->first();
        $validated = $this->validate($request, [
            "item_id" => 'numeric|required',
            "quantity" => 'numeric|required',
            "comment" => 'string|required',
        ]);
        // get item by id
        $item = Item::where(["id" => $validated["item_id"]])->first();
        if (!$item) return response()->json(["message" => "Item not found"], Response::HTTP_UNPROCESSABLE_ENTITY);
        // store item request
        $itemRequest = new ItemRequest();
        $itemRequest->school_id = $school['id'];
        $itemRequest->item_id = $validated["item_id"];
        $itemRequest->user_id = $user['id'];
        $itemRequest->quantity = $validated["quantity"];
        $itemRequest->comment = $validated["comment"];
        $itemRequest->save();
        return response()->json(["itemRequest" => $itemRequest], Response::HTTP_CREATED);
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
            "user" => [
                "id" => $model['user']['id'],
                "name" => $model['user']['name'],
            ],
            "comment" => $model['comment'],
            "quantity" => $model['quantity'],
            "status" => $model['status'],
        ];
    }
}
