<?php

namespace App\Http\Controllers;

use App\Models\Via;
use App\Http\Requests\ViaStoreRequest;
use Illuminate\Http\Request;

class ViaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $via = Via::select('id','uid','created_date','country','cost')->where('status', 0)->get();
        return response()->json([
            'data' => $via
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ViaStoreRequest $request)
    {
        $via = new Via;
        $via->fill($request->all());
        $via->save();

        return response()->json([
            'status' => true,
            'created' => true,
            'data' => [
                'id' => $via->id
            ]
        ]);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
