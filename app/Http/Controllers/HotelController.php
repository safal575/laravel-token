<?php
namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->user
            ->hotels()
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'sku', 'price', 'quantity');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'sku' => 'required',
            'price' => 'required',
            'quantity' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => 'Please provide the correct crendentials.'], 200);
        }

        //Request is valid, create new Hotel
        $hotel = $this->user->hotels()->create([
            'name' => $request->name,
            'sku' => $request->sku,
            'price' => $request->price,
            'quantity' => $request->quantity
        ]);

        //Hotel created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Hotel created successfully',
            'data' => $hotel
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $hotel = $this->user->hotels()->find($id);
    
        if (!$hotel) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Hotel not found.'
            ], 400);
        }
    
        return $hotel;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function edit(Hotel $hotel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hotel $hotel)
    {
        //Validate data
        $data = $request->only('name', 'sku', 'price', 'quantity');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'sku' => 'required',
            'price' => 'required',
            'quantity' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => 'Provided crendentials not matched!'], 200);
        }

        //Request is valid, update Hotel
        $Hotel = $hotel->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'price' => $request->price,
            'quantity' => $request->quantity
        ]);

        //Hotel updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'Hotel updated successfully',
            'data' => $hotel
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hotel  $Hotel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'hotel deleted successfully'
        ], Response::HTTP_OK);
    }
}