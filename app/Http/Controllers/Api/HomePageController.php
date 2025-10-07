<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bike;
use Illuminate\Support\Facades\DB;
class HomePageController extends Controller
{
    public function index()
{
    $bike = DB::table('bikes as b')
    ->join('stores as s', 'b.store_id', '=', 's.id')
    ->select(
        'b.*',
        's.name as store_name',
        's.id as store_id',
        's.logo as store_logo',
        's.*'
    )
    ->get();


    return response()->json($bike);
}
}
