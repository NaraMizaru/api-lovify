<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhotographerController extends Controller
{
    public function createPhotographer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'required|file|mimes:png,jpg,jpeg'
        ]);

        if ($request->user()->role != 'admin') {
            return response()->json([
                'message' => 'Forbidden access',
            ], 401);
        }

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid fields',
                'error' => $validator->errors()
            ], 400);
        }

        $photographer = new Photographer();
        $photographer->name = $request->name;
        $photographer->price = $request->price;
        $photographer->description = $request->description;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $photographer->image = $request->file('image')->storeAs('/photographer/' . $imageName);
        }
        $photographer->save();

        return response()->json([
            'message' => 'Photographer created successfully',
            'photographer' => $photographer
        ], 201);
    }

    public function updatePhotographer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'file|mimes:png,jpg,jpeg',
        ]);

        if ($request->user()->role != 'admin') {
            return response()->json([
                'message' => 'Forbidden access',
            ], 401);
        }

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid fields',
                'error' => $validator->errors()
            ], 400);
        }

        $photographer = Photographer::find($id);

        if (!$photographer) {
            return response()->json([
                'message' => 'Photographer not found',
            ], 404);
        }


        $photographer->name = $request->name;
        $photographer->price = $request->price;
        $photographer->description = $request->description;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $photographer->image = $request->file('image')->storeAs('/photographer/' . $imageName);
        } else {
            unset($request['image']);
        }
        $photographer->save();

        return response()->json([
            'message' => 'Update success',
            'photographer' => $photographer
        ], 200);
    }

    public function deletePhotographer(Request $request, $id)
    {
        $photographer = Photographer::find($id);

        if ($request->user()->role != 'admin') {
            return response()->json([
                'message' => 'Forbidden access'
            ], 403);
        }

        if (!$photographer) {
            return response()->json([
                'message' => 'Photographer not found'
            ], 404);
        }

        $photographer->delete();
        return response([], 204);
    }

    public function getPhotographer()
    {
        $photographer = Photographer::all();

        return response()->json([
            'message' => 'Get all Photographer',
            'photographer' => $photographer
        ], 200);
    }
}
