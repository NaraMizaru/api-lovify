<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Catering;
use App\Models\Decoration;
use App\Models\Mua;
use App\Models\Packet;
use App\Models\PacketAttachment;
use App\Models\Photographer;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PacketController extends Controller
{
    public function createPacket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'decoration_id' => 'required',
            'venue_id' => 'required',
            'mua_id' => 'required',
            'catering_id' => 'required',
            'photographer_id' => 'required',
        ]);

        if (!$request->user()) {
            return response()->json([
                'message' => 'Forbidden access',
            ], 403);
        }

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid fields',
                'error' => $validator->errors(),
            ], 400);
        }

        $catering = Catering::where('id', $request->catering_id)->first();
        $decoration = Decoration::where('id', $request->decoration_id)->first();
        $mua = Mua::where('id', $request->mua_id)->first();
        $photographer = Photographer::where('id', $request->photographer_id)->first();
        $venue = Venue::where('id', $request->venue_id)->first();
        $totalGuest = $venue->total_guest;

        $cateringPrice = $catering->price * $totalGuest;
        $decorationPrice= $decoration->price;
        $muaPrice = $mua->price * 2;
        $photographerPrice = $photographer->price;
        $venuePrice = $venue->price;

        $totalPrice = $cateringPrice + $photographerPrice + $venuePrice + $muaPrice + $decorationPrice;
        // $price = $catering->price + $decoration->price + $venue->price + $photographer->price + $mua->price;

        $packet = new Packet();
        $packet->name = $request->name;
        $packet->price = $totalPrice;
        $packet->description = $request->description;
        $packet->save();

        if ($packet->save()) {
            $packetAttachment = new PacketAttachment();
            $packetAttachment->decoration_id = $request->decoration_id;
            $packetAttachment->venue_id = $request->venue_id;
            $packetAttachment->mua_id = $request->mua_id;
            $packetAttachment->catering_id = $request->catering_id;
            $packetAttachment->photographer_id = $request->photographer_id;
            $packetAttachment->packet_id = $packet->id;
            $packetAttachment->save();

            if ($packetAttachment->save()) {
                $response = Packet::where('id', $packet->id)->with('packetAttachment')->first();
                return response()->json([
                    'message' => 'Packet created successfully',
                    'packet' => $response,
                ], 201);
            }
        }
    }

    public function updatePacket(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'decoration_id' => 'required',
            'venue_id' => 'required',
            'mua_id' => 'required',
            'catering_id' => 'required',
            'photographer_id' => 'required',
        ]);

        if ($request->user()->role != 'admin') {
            return response()->json([
                'message' => 'Forbidden access',
            ], 403);
        }

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid fields',
                'error' => $validator->errors(),
            ], 400);
        }

        $catering = Catering::where('id', $request->catering_id)->first();
        $decoration = Decoration::where('id', $request->decoration_id)->first();
        $mua = Mua::where('id', $request->mua_id)->first();
        $photographer = Photographer::where('id', $request->photographer_id)->first();
        $venue = Venue::where('id', $request->venue_id)->first();
        $totalGuest = $venue->total_guest;

        $cateringPrice = $catering->price * $totalGuest;
        $decorationPrice= $decoration->price;
        $muaPrice = $mua->price * 2;
        $photographerPrice = $photographer->price;
        $venuePrice = $venue->price;

        $totalPrice = $cateringPrice + $photographerPrice + $venuePrice + $muaPrice + $decorationPrice;
        
        $packet = Packet::find($id);

        if (!$packet) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }
        $packetAttachment = PacketAttachment::where('packet_id', $packet->id)->first();

        $packet->name = $request->name;
        $packet->name = $request->name;
        $packet->price = $totalPrice;
        $packet->description = $request->description;
        $packet->save();

        if ($packet->save()) {
            $packetAttachment->decoration_id = $request->decoration_id;
            $packetAttachment->venue_id = $request->venue_id;
            $packetAttachment->mua_id = $request->mua_id;
            $packetAttachment->catering_id = $request->catering_id;
            $packetAttachment->photographer_id = $request->photographer_id;
            $packetAttachment->packet_id = $packet->id;
            $packetAttachment->save();

            if ($packetAttachment->save()) {
                $response = Packet::where('id', $id)->with('packetAttachment')->get();
                return response()->json([
                    'message' => 'Packet updated successfully',
                    'packet' => $response,
                ]);
            }
        }
    }

    public function deletePacket(Request $request, $id)
    {
        $packet = Packet::find($id);

        if ($request->user()->role != 'admin') {
            return response()->json([
                'message' => 'Forbidden access'
            ], 403);
        }

        if (!$packet) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $packet->delete();
        return response([], 204);
    }

    public function getDetailPacket($id)
    {
        $packet = Packet::with('packetAttachment')->where('id', $id)->first();

        if (!$packet) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Get detail packet',
            'packet' => $packet
        ]);
    }

    public function getAllPacket()
    {
        $packet = Packet::with('packetAttachment')->get();

        return response()->json([
            'message' => 'Get all packet',
            'packet' => $packet
        ]);
    }
}
