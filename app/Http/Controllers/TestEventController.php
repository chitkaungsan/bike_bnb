<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\TestEvent;
class TestEventController extends Controller
{
    public function fire()
    {
        event(new TestEvent());

        return response()->json(['status' => 'Event broadcasted!']);
    }
}
