<?php

namespace App\Http\Controllers;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GeneralController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->hasFile('file')){
           
            
             $file = $request->file('file');

            $imageName = time() . $file->getClientOriginalName();
            $filePath = $file->move(public_path('items'), $imageName);
        //  $uploadedUrl = Cloudinary::upload($request->file('file')->getRealPath())->getSecurePath();
        //     return response()->json(['url' => $uploadedUrl, "success" => true], Response::HTTP_OK);
             return response()->json(['url' => "http://localhost:8000/items/" . $imageName , "success" => true], Response::HTTP_OK);
        }
        return response()->json(['success' => false, 'message' => "Please upload a file"], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
