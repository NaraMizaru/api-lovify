<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Decoration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DecorationController extends Controller
{
    public function createDecoration(Request $request)
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

        $decoration = new Decoration();
        $decoration->name = $request->name;
        $decoration->price = $request->price;
        $decoration->description = $request->description;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $decoration->image = $request->file('image')->storeAs('/decoration/' . $imageName);
        }
        $decoration->save();

        return response()->json([
            'message' => 'Decoration created successfully',
            'decoration' => $decoration
        ], 201);
    }

    public function updateDecoration(Request $request, $id)
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

        $decoration = Decoration::find($id);

        if (!$decoration) {
            return response()->json([
                'message' => 'Decoration not found',
            ], 404);
        }


        $decoration->name = $request->name;
        $decoration->price = $request->price;
        $decoration->description = $request->description;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $decoration->image = $request->file('image')->storeAs('/decoration/' . $imageName);
        } else {
            unset($request['image']);
        }
        $decoration->save();

        return response()->json([
            'message' => 'Update success',
            'decoration' => $decoration
        ], 200);
    }

    public function deleteDecoration(Request $request, $id)
    {
        $decoration = Decoration::find($id);

        if ($request->user()->role != 'admin') {
            return response()->json([
                'message' => 'Forbidden access'
            ], 403);
        }

        if (!$decoration) {
            return response()->json([
                'message' => 'Decoration not found'
            ], 404);
        }

        $decoration->delete();
        return response([], 204);
    }

    public function getDecoration()
    {
        $decoration = Decoration::all();

        return response()->json([
            'message' => 'Get all decoration',
            'decoration' => $decoration
        ], 200);
    }
}
