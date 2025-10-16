<?php

namespace App\Repositories\Contracts;

interface BookingRepositoryInterface
{
    public function calculateRentalPrice($bikeId, $startDate, $endDate);
    public function createBooking($data);
    public function findById($id);
    public function findByBikeId($bikeId);
}
