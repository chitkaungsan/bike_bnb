<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\Eloquent\BikeFilterRepository;

class BikeFilterController extends Controller
{
    protected $bikeFilterRepository;

    // Inject BikeFilterRepository instead of BookingRepositoryInterface
    public function __construct(BikeFilterRepository $bikeFilterRepository)
    {
        $this->bikeFilterRepository = $bikeFilterRepository;
    }

    /**
     * Handle bike filter request
     */
    public function bikeFilter(Request $request)
    {
        // Prepare clean filter params
        $params = [
            'location_id' => $request->input('location_id'),
            'category_id' => $request->input('category_id'),
            'start_date'  => $request->input('start_date'),
            'end_date'    => $request->input('end_date'),
        ];

        // Call repository logic (DB::table style inside repository)
        $bikes = $this->bikeFilterRepository->filterBikes($params);

        // Return JSON response
        return response()->json($bikes);
    }
    public function searchBikesFilter(Request $request) {
        $params = [
            'location_id' => $request->input('location_id'),
            'category_id' => $request->input('category_id'),
            'start_date'  => $request->input('start_date'),
            'end_date'    => $request->input('end_date'),
        ];
        $bikes = $this->bikeFilterRepository->searchBikesFilter($params);
        return response()->json($bikes);
    }
}
