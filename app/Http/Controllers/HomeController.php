<?php

namespace App\Http\Controllers;
use App\Models\Via;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function show()
    {
        $via = Via::select('*')->where('status', 0)->count();
        $via_sell = Via::select('*')->where('status', 1)->count();
        $user = User::select('*')->count();
        $home['via'] = $via;
        $home['via_sell'] = $via_sell;
        $home['user'] = $user;
        return response()->json([
            'data' => $home
        ]);
    }
}
