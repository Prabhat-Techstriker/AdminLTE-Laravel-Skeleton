<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Challenges;

class ChallengesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $Challenges = Challenges::get();
            return response()->json(['success' => true, 'challenges' => $Challenges, 'message' => 'challenges fetched successfully!.'], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'failed to fetched challenges!.'], 404);
        }
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
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|string',
        ]);

        try {
            $Challenges = Challenges::create($request->all());
            return response()->json(['success' => true, 'challenges' => $Challenges , 'message' => 'challenges created successfully!.'], 200);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'failed to created challenges!.'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Challenges::where('id', $id)->exists()) {
            $challenges = Challenges::find($id);

            $challenges->name               = is_null($request->name) ? $challenges->name : $request->name;
            $challenges->description        = is_null($request->description) ? $challenges->description : $request->description;
            $challenges->price              = is_null($request->price) ? $challenges->price : $request->price;
            $challenges->challenge_period   = is_null($request->challenge_period) ? $challenges->challenge_period : $request->challenge_period;
            $challenges->challenge_goal     = is_null($request->challenge_goal) ? $challenges->challenge_goal : $request->challenge_goal;

            $challenges->save();
            return response()->json(['success' => true, 'challenges' => $challenges, "message" => "challenge updated successfully."], 200);
        } else {
            return response()->json(['success' => false, "message" => "failed to update challenge!."], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
