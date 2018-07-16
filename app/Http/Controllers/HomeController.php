<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use DB;
use Intervention\Image\Facades\Image;

class HomeController extends Controller
{
    //return list of plans
    public function plans()
    {
        $plans = DB::table('plans')->get();
        return response($plans);
    }

    public function index()
    {
        return view('home');
    }
}
