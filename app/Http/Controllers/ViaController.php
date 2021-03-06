<?php

namespace App\Http\Controllers;

use App\Models\Via;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ViaStoreRequest;
use App\Models\ViaHistory;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

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

    public function index()
    {
        $via = Via::select('id', 'uid', 'created_date', 'country', 'cost')->where('status', 0)->get();
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
        if (Auth::user()->is_admin == 1) {
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
    }

    public function buy($via)
    {
        $via = Via::select('id', 'cost')->where('id', $via)->first();
        if (Auth::user()->money - $via->cost < 0) {
            return response()->json([
                'data' => [
                    'message' => 1,
                ]
            ]);
        } else {
            try {
                User::where('id', Auth::user()->id)
                    ->update(['money' => Auth::user()->money - $via->cost]);
                $viaHistory = new ViaHistory;
                $viaHistory->via_id = $via->id;
                $viaHistory->user_id = Auth::user()->id;
                $viaHistory->save();
                Via::where('id', $via->id)
                    ->update(['status' => 1]);
                return response()->json([
                    'status' => true,
                    'created' => true,
                    'data' => [
                        'id' => $via->id
                    ]
                ]);
            } catch (\Exception $e) {
                return '';
            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($via)
    {
        if (Auth::user()->is_admin == 1) {
            $via = Via::select('*')->where('id', $via)->first();
            return response()->json([
                'data' => $via
            ]);
        } else {
            $via = Via::select('id', 'uid', 'created_date', 'country', 'cost')->where('id', $via)->first();
            $avatar = $this->checkAvatar($via->uid);
            $via['avatar'] = $avatar['data']['url'];
            return response()->json([
                'data' => $via
            ]);
        }
    }

    private function checkAvatar($uid)
    {
        $client = new Client();
        try {
            $url = "https://graph.facebook.com/" . $uid . "/picture?type=square&redirect=false";
            $response = $client->request('GET', $url);
            $imgInfo = $response->getBody()->getContents();
            $imgInfo = json_decode($imgInfo, true);
            return $imgInfo;
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ViaStoreRequest $request, Via $via)
    {
        if (Auth::user()->is_admin == 1) {
            $via->fill($request->all());
            $via->save();
            return response()->json([
                'status' => true,
                'data' => $via
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Via $via)
    {
        if (Auth::user()->is_admin == 1) {
            $via->delete();

            return response()->json([
                'status' => true
            ]);
        }
    }

    /**
     * Destroy resources by ids
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroyMass(Request $request)
    {
        $request->validate([
            'ids' => 'required|array'
        ]);
        Via::destroy($request->ids);

        return response()->json([
            'status' => true
        ]);
    }
}
