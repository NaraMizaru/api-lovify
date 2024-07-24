<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Catering;
use App\Models\Decoration;
use App\Models\Mua;
use App\Models\Photographer;
use App\Models\Planning;
use App\Models\Venue;
use App\Models\Wedding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WeddingController extends Controller
{
    public function createWeddingPacket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'date' => 'required',
            'packet_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'error' => $validator->errors()
            ], 400);
        }

        $user = $request->user();
        $wedding = new Wedding();
        $wedding->name = $request->name;
        $wedding->date = $request->date;
        $wedding->packet_id = $request->packet_id;
        $wedding->user_id = $user->id;
        $wedding->save();

        $response = Wedding::where('id', $wedding->id)->with(['packet', 'packet.packetAttachment'])->first();
        return response()->json([
            'message' => 'Wedding packet created successfully',
            'wedding' => $response
        ], 201);
    }

    public function updateWeddingPacket(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'date' => 'required',
            'packet_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'error' => $validator->errors()
            ], 400);
        }


        $user = $request->user();
        $wedding = Wedding::find($id);
        if (!$wedding) {
            return response()->json([
                'message' => 'Wedding not found'
            ], 404);
        }

        if ($wedding->user_id != $user->id) {
            return response()->json([
                'message' => 'Forbidden access'
            ], 403);
        }

        $wedding->name = $request->name;
        $wedding->date = $request->date;
        $wedding->packet_id = $request->packet_id;
        $wedding->save();

        $response = Wedding::where('id', $wedding->id)->with([
            'packet',
            'packet.packetAttachment.decoration',
            'packet.packetAttachment.venue',
            'packet.packetAttachment.mua',
            'packet.packetAttachment.catering',
            'packet.packetAttachment.photographer'
        ])->first();

        return response()->json([
            'message' => 'Wedding packet updated successfully',
            'wedding' => $response
        ], 200);
    }

    public function createWeddingPlanning(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'date' => 'required',
            'decoration_id' => 'nullable',
            'venue_id' => 'nullable',
            'mua_id' => 'nullable',
            'catering_id' => 'nullable',
            'photographer_id' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'error' => $validator->errors()
            ], 400);
        }

        $catering = Catering::where('id', $request->catering_id)->first();
        $decoration = Decoration::where('id', $request->decoration_id)->first();
        $mua = Mua::where('id', $request->mua_id)->first();
        $photographer = Photographer::where('id', $request->photographer_id)->first();
        $venue = Venue::where('id', $request->venue_id)->first();
        $totalGuest = $venue->total_guest;

        $cateringPrice = $catering->price * $totalGuest ?? 0;
        $decorationPrice = $decoration->price ?? 0;
        $muaPrice = $mua->price * 2 ?? 0;
        $photographerPrice = $photographer->price ?? 0;
        $venuePrice = $venue->price ?? 00;

        $totalPrice = $cateringPrice + $photographerPrice + $venuePrice + $muaPrice + $decorationPrice;
        $user = $request->user();

        $planning = new Planning();
        $planning->decoration_id = $request->decoration_id;
        $planning->venue_id = $request->venue_id;
        $planning->mua_id = $request->mua_id;
        $planning->catering_id = $request->catering_id;
        $planning->photographer_id = $request->photographer_id;
        $planning->price = $totalPrice;
        $planning->save();

        if ($planning->save()) {
            $wedding = new Wedding();
            $wedding->name = $request->name;
            $wedding->date = $request->date;
            $wedding->planning_id = $planning->id;
            $wedding->user_id = $user->id;
            $wedding->save();

            if ($wedding->save()) {
                $response = Wedding::where('id', $wedding->id)->with([
                    'planning',
                    'planning.decoration',
                    'planning.venue',
                    'planning.mua',
                    'planning.catering',
                    'planning.photographer'
                ])->first();

                return response()->json([
                    'message' => 'Wedding planning created successfully',
                    'wedding' => $response
                ], 201);
            }
        }
    }

    public function updateWeddingPlanning(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'date' => 'required',
            'decoration_id' => 'nullable',
            'venue_id' => 'nullable',
            'mua_id' => 'nullable',
            'catering_id' => 'nullable',
            'photographer_id' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'error' => $validator->errors()
            ], 400);
        }

        $catering = Catering::where('id', $request->catering_id)->first();
        $decoration = Decoration::where('id', $request->decoration_id)->first();
        $mua = Mua::where('id', $request->mua_id)->first();
        $photographer = Photographer::where('id', $request->photographer_id)->first();
        $venue = Venue::where('id', $request->venue_id)->first();
        $totalGuest = $venue->total_guest;

        $cateringPrice = $catering->price * $totalGuest ?? 0;
        $decorationPrice = $decoration->price ?? 0;
        $muaPrice = $mua->price * 2 ?? 0;
        $photographerPrice = $photographer->price ?? 0;
        $venuePrice = $venue->price ?? 00;

        $totalPrice = $cateringPrice + $photographerPrice + $venuePrice + $muaPrice + $decorationPrice;
        $user = $request->user();

        $planning = Planning::find($id);
        $wedding = Wedding::where('planning_id', $planning->id)->first();

        if (!$wedding) {
            return response()->json([
                'message' => 'Wedding not found'
            ], 404);
        }

        if ($wedding->user_id != $user->id) {
            return response()->json([
                'message' => 'Forbidden access'
            ], 403);
        }

        $planning->decoration_id = $request->decoration_id;
        $planning->venue_id = $request->venue_id;
        $planning->mua_id = $request->mua_id;
        $planning->catering_id = $request->catering_id;
        $planning->photographer_id = $request->photographer_id;
        $planning->price = $totalPrice;
        $planning->save();

        if ($planning->save()) {
            $wedding->name = $request->name;
            $wedding->date = $request->date;
            $wedding->save();

            if ($wedding->save()) {
                $response = Wedding::where('id', $wedding->id)->with([
                    'planning',
                    'planning.decoration',
                    'planning.venue',
                    'planning.mua',
                    'planning.catering',
                    'planning.photographer'
                ])->first();

                return response()->json([
                    'message' => 'Wedding planning updated successfully',
                    'wedding' => $response
                ], 200);
            }
        }
    }

    public function deleteWedding(Request $request, $id)
    {
        $user = $request->user();
        $wedding = Wedding::find($id);
        if (!$wedding) {
            return response()->json([
                'message' => 'Wedding not found'
            ], 404);
        }

        if ($wedding->user_id != $user->id) {
            return response()->json([
                'message' => 'Forbidden access'
            ], 403);
        }

        $wedding->delete();
        return response([], 204);
    }

    public function getUserWedding(Request $request)
    {
        $user = $request->user();
        $weddings = Wedding::where('user_id', $user->id)->with([
            'packet.packetAttachment.venue',
            'packet.packetAttachment.decoration',
            'packet.packetAttachment.mua',
            'packet.packetAttachment.catering',
            'packet.packetAttachment.photographer',
            'planning.venue',
            'planning.decoration',
            'planning.mua',
            'planning.catering',
            'planning.photographer'
            ])->get();
        return response()->json([
            'message' => 'Get wedding user logged in',
            'weddings' => $weddings
        ], 200);
    }

    public function getAllWedding()
    {
        $weddings = Wedding::with([
            'packet.packetAttachment.venue',
            'packet.packetAttachment.decoration',
            'packet.packetAttachment.mua',
            'packet.packetAttachment.catering',
            'packet.packetAttachment.photographer',
            'planning.venue',
            'planning.decoration',
            'planning.mua',
            'planning.catering',
            'planning.photographer'
        ])->get();
        return response()->json([
            'message' => 'Get all weddings',
            'weddings' => $weddings
        ], 200);
    }
}
