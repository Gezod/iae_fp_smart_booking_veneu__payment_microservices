<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index()
    {
        return response()->json(Venue::all());
    }

    public function show($id)
    {
        $venue = Venue::find($id);
        return $venue ? response()->json($venue) : response()->json(['error' => 'Not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'            => 'required|string',
            'location'        => 'required|string',
            'price_per_hour'  => 'required|integer|min:0',
            'available_slots' => 'required|integer|min:0',
        ]);

        $venue = Venue::find($id);
        if (!$venue) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $venue->update($data);

        return response()->json($venue);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string',
            'location'        => 'required|string',
            'price_per_hour'  => 'required|integer|min:0',
            'available_slots' => 'required|integer|min:0',
        ]);

        $venue = Venue::create($data);
        return response()->json($venue, 201);
    }

    public function reduceSlot(Request $request, $id)
    {
        $request->validate([
            'slot' => 'required|integer|min:1'
        ]);

        $venue = Venue::find($id);
        if (!$venue) return response()->json(['error' => 'Not found'], 404);

        if ($venue->available_slots < $request->slot) {
            return response()->json(['error' => 'Not enough slots'], 400);
        }

        $venue->available_slots -= $request->slot;
        $venue->save();

        return response()->json(['message' => 'Slot reduced']);
    }

    public function destroy($id)
    {
        $venue = Venue::find($id);
        if (!$venue) return response()->json(['error' => 'Not found'], 404);

        $venue->delete();
        return response()->json(['message' => 'Venue deleted']);
    }


    public function decrementSlot(Request $request, $id)
    {
        $request->validate(['slot' => 'required|integer|min:1']);

        $venue = Venue::find($id);
        if (!$venue) return response()->json(['error' => 'Not found'], 404);

        if ($venue->available_slots < $request->slot) {
            return response()->json(['error' => 'Slot tidak cukup'], 400);
        }

        $venue->available_slots -= $request->slot;
        $venue->save();

        return response()->json(['message' => 'Slot dikurangi']);
    }

    public function incrementSlot(Request $request, $id)
    {
        $request->validate(['slot' => 'required|integer|min:1']);

        $venue = Venue::find($id);
        if (!$venue) return response()->json(['error' => 'Not found'], 404);

        $venue->available_slots += $request->slot;
        $venue->save();

        return response()->json(['message' => 'Slot ditambahkan']);
    }
}
