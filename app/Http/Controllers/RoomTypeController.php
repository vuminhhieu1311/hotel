<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomTypeRequest;
use App\Http\Requests\UpdateRoomTypeRequest;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roomTypes = RoomType::all();

        return view('room-type.index', compact('roomTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('room-type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomTypeRequest $request)
    {
        // RoomType::create($request->all());


        try {
            DB::beginTransaction();
            $filePath = optional($request->file('avatar'))->store('images', ['disk' => 'public_storage']);

            $roomType = RoomType::create([
                'name' => $request->name,
                'avatar' => $filePath,
                'max_adults' => $request->max_adults,
                'max_children' => $request->max_children,
                'price' => $request->price,
                'description' => $request->description,
            ]);

            foreach ($request->file('images', []) as $image) {
                $filePath = $image->store('images', ['disk' => 'public_storage']);
                $roomType->images()->create(['url' => $filePath]);
            }

            DB::commit();

            // return redirect()->route('room-types.index')
            // ->with('success', __('messages.successfully'));
            return response()->json($roomType);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(RoomType $roomType)
    {
        return $roomType;
    }

    /**
     * Display the specified resource.
     */
    public function showPublicRoomType(RoomType $roomType)
    {
        return response()->json($roomType);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomType $roomType)
    {
        return view('room-type.edit', compact('roomType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomTypeRequest $request, RoomType $roomType)
    {
        $roomType->update($request->all());

        return redirect()->route('room-types.index')
            ->with('success', __('messages.successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomType $roomType)
    {
        return $roomType->delete();
    }
}
