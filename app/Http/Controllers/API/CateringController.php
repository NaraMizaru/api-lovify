<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Catering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CateringController extends Controller
{
    public function createCatering(Request $request)
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

        $catering = new Catering();
        $catering->name = $request->name;
        $catering->price = $request->price;
        $catering->description = $request->description;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $catering->image = $request->file('image')->storeAs('/catering/' . $imageName);
        }
        $catering->save();

        return response()->json([
            'message' => 'Catering created successfully',
            'catering' => $catering
        ], 201);
    }

    public function updateCatering(Request $request, $id)
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

        $catering = Catering::find($id);

        if (!$catering) {
            return response()->json([
                'message' => 'Catering not found',
            ], 404);
        }

        $catering->name = $request->name;
        $catering->price = $request->price;
        $catering->description = $request->description;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $catering->image = $request->file('image')->storeAs('/catering/' . $imageName);
        } else {
            unset($request['image']);
        }
        $catering->save();

        return response()->json([
            'message' => 'Update success',
            'catering' => $catering
        ], 200);
    }

    public function deleteCatering(Request $request, $id)
    {
        $catering = Catering::find($id);

        if ($request->user()->role != 'admin') {
            return response()->json([
                'message' => 'Forbidden access'
            ], 403);
        }

        if (!$catering) {
            return response()->json([
                'message' => 'Catering not found'
            ], 404);
        }

        $catering->delete();
        return response([], 204);
    }

    public function getCatering()
    {
        $catering = Catering::all();

        return response()->json([
            'message' => 'Get all Catering',
            'catering' => $catering
        ], 200);
    }
}
