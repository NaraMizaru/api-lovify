<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Catering;
use App\Models\Mua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MuaController extends Controller
{
    public function createMua(Request $request)
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

        $mua = new Mua();
        $mua->name = $request->name;
        $mua->price = $request->price;
        $mua->description = $request->description;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $mua->image = $request->file('image')->storeAs('/mua/' . $imageName);
        }
        $mua->save();

        return response()->json([
            'message' => 'Mua created successfully',
            'mua' => $mua
        ], 201);
    }

    public function updateMua(Request $request, $id)
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

        $mua = Mua::find($id);

        if (!$mua) {
            return response()->json([
                'message' => 'Mua not found',
            ], 404);
        }

        $mua->name = $request->name;
        $mua->price = $request->price;
        $mua->description = $request->description;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $mua->image = $request->file('image')->storeAs('/mua/' . $imageName);
        } else {
            unset($request['image']);
        }
        $mua->save();

        return response()->json([
            'message' => 'Update success',
            'mua' => $mua
        ], 200);
    }

    public function deleteMua(Request $request, $id)
    {
        $mua = Mua::find($id);

        if ($request->user()->role != 'admin') {
            return response()->json([
                'message' => 'Forbidden access'
            ], 403);
        }

        if (!$mua) {
            return response()->json([
                'message' => 'Mua not found'
            ], 404);
        }

        $mua->delete();
        return response([], 204);
    }

    public function getMua()
    {
        $mua = Mua::all();

        return response()->json([
            'message' => 'Get all Mua',
            'mua' => $mua
        ], 200);
    }
}
