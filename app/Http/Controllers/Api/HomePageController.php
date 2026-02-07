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
   $bikes = Bike::with(['store' => function($query) {
    $query->select('id', 'name', 'logo');
}])->get();

    return response()->json($bikes);
}
}
