<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Facades\DB;
use App\Models\Bike;
use App\Models\Store;
use App\Models\City;
use App\Models\Category;
use App\Models\Booking;

class BikeFilterRepository
{
    public function filterBikes($params)
    {
        // return response()->json($params);
        $location_id = $params['location_id'] ?? null;
        $category_id = $params['category_id'] ?? null;
        $start_date  = $params['start_date'] ?? null;
        $end_date    = $params['end_date'] ?? null;

        $query = DB::table('bikes')
            ->select(
                'bikes.id',
                'bikes.title',
                'bikes.price',
                'bikes.photo',
                'bikes.model',
                'bikes.year',
                'stores.name as store_name',
                'stores.logo as store_logo',
                'cities.name as city_name',
                'categories.name as category_name'
            )
            ->join('stores', 'bikes.store_id', '=', 'stores.id')
            ->leftJoin('cities', 'stores.city_id', '=', 'cities.id')
            ->leftJoin('categories', 'bikes.cat_id', '=', 'categories.id')
            ->where('bikes.status', '=', 'active');

        if (!empty($location_id)) {
            $query->where('stores.city_id', $location_id);
        }

        if (!empty($category_id) && $category_id !== 'all') {
            $query->where('bikes.cat_id', $category_id);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $query->whereNotExists(function ($subQuery) use ($start_date, $end_date) {
                $subQuery->select(DB::raw(1))
                    ->from('bookings')
                    ->whereRaw('bookings.bike_id = bikes.id')
                    ->where(function ($q) use ($start_date, $end_date) {
                        $q->whereBetween('bookings.start_date', [$start_date, $end_date])
                            ->orWhereBetween('bookings.end_date', [$start_date, $end_date])
                            ->orWhere(function ($inner) use ($start_date, $end_date) {
                                $inner->where('bookings.start_date', '<', $start_date)
                                    ->where('bookings.end_date', '>', $end_date);
                            });
                    });
            });
        }

        return $query->paginate(20);
    }
    public function searchBikesFilter($params)
    {
        // return response()->json($params);
        $location_id = $params['location_id'] ?? null;
        $category_id = $params['category_id'] ?? null;
        $start_date  = $params['start_date'] ?? null;
        $end_date    = $params['end_date'] ?? null;

        $query = DB::table('bikes')
            ->select(
                'bikes.id',
                'bikes.title',
                'bikes.price',
                'bikes.photo',
                'bikes.model',
                'bikes.year',
                'stores.name as store_name',
                'stores.logo as store_logo',
                'cities.name as city_name',
                'categories.name as category_name'
            )
            ->join('stores', 'bikes.store_id', '=', 'stores.id')
            ->leftJoin('cities', 'stores.city_id', '=', 'cities.id')
            ->leftJoin('categories', 'bikes.cat_id', '=', 'categories.id')
            ->where('bikes.status', '=', 'active');

        if (!empty($location_id)) {
            $query->where('stores.city_id', $location_id);
        }

        if (!empty($category_id) && $category_id !== 'all') {
            $query->where('bikes.cat_id', $category_id);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $query->whereNotExists(function ($subQuery) use ($start_date, $end_date) {
                $subQuery->select(DB::raw(1))
                    ->from('bookings')
                    ->whereRaw('bookings.bike_id = bikes.id')
                    ->where(function ($q) use ($start_date, $end_date) {
                        $q->whereBetween('bookings.start_date', [$start_date, $end_date])
                            ->orWhereBetween('bookings.end_date', [$start_date, $end_date])
                            ->orWhere(function ($inner) use ($start_date, $end_date) {
                                $inner->where('bookings.start_date', '<', $start_date)
                                    ->where('bookings.end_date', '>', $end_date);
                            });
                    });
            });
        }

        return $query->paginate(20);
    }
}
