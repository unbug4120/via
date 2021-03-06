<?php

namespace App\Http\Controllers;

use App\Models\ViaHistory;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class ViaPanelController extends Controller
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

    public function show($via)
    {
        $via = ViaHistory::find(Auth::user()->id)->via()->first();
        $avatar = $this->checkAvatar($via->uid);
        $via['avatar'] = $avatar['data']['url'];
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


    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $via = ViaHistory::find(Auth::user()->id)->via()->get();
        return response()->json([
            'data' => $via
        ]);
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
