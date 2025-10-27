<?php

namespace App\Repositories\Contracts;

interface BikeFilterRepositoryInterface
{
public function filterBikes($params);
public function searchBikesFilter($params);
}