<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VenueController extends Controller
{
    public function createVenue(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'total_guest' => 'required|numeric',
            'description' => 'required',
            'price' => 'required|numeric',
            'address' => 'required',
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

        $venue = new Venue();
        $venue->name = $request->name;
        $venue->total_guest = $request->total_guest;
        $venue->description = $request->description;
        $venue->price = $request->price;
        $venue->address = $request->address;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $venue->image = $request->file('image')->storeAs('/venue/' . $imageName);
        }
        $venue->save();

        return response()->json([
            'message' => 'Venue created successfully',
            'venue' => $venue
        ], 201);
    }

    public function updateVenue(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'total_guest' => 'required|numeric',
            'description' => 'required',
            'price' => 'required|numeric',
            'address' => 'required',
            'image' => 'file|mimes:png,jpg,jpeg'
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

        $venue = Venue::find($id);

        if (!$venue) {
            return response()->json([
                'message' => 'Venue not found',
            ], 404);
        }

        $venue->name = $request->name;
        $venue->total_guest = $request->total_guest;
        $venue->description = $request->description;
        $venue->price = $request->price;
        $venue->address = $request->address;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $venue->image = $request->file('image')->storeAs('/venue/' . $imageName);
        } else {
            unset($request['image']);
        }
        $venue->save();

        return response()->json([
            'message' => 'Update success',
            'venue' => $venue
        ], 200);
    }

    // public function detailVenue(Request $request, $id)
    // {
    //     $venue = Venue::find($id);

    //     if (!$venue) {
    //         return response()->json([
    //             'message' => 'Venue not found',
    //         ], 404);
    //     }

    //     return response()->json([
    //         'message' => 'Detail venue',
    //         'venue' => $venue
    //     ], 200);
    // }

    public function deleteVenue(Request $request, $id)
    {
        $venue = Venue::find($id);

        if ($request->user()->role != 'admin') {
            return response()->json([
                'message' => 'Forbidden access'
            ], 403);
        }

        if (!$venue) {
            return response()->json([
                'message' => 'Venue not found'
            ], 404);
        }

        $venue->delete();
        return response([], 204);
    }

    public function getVenue()
    {
        $venue = Venue::all();

        return response()->json([
            'message' => 'Get all Venue',
            'venue' => $venue
        ], 200);
    }
}
