<?php

namespace App\Repositories\Contracts;

interface BookingRepositoryInterface
{
    public function calculateRentalPrice($bikeId, $startDate, $endDate);
    public function createBooking($data);
    public function findById($id);
    public function findByBikeId($bikeId);
    public function getBookingsByUserId($userId);
    public function getBookingsByOwnerId($ownerId);
    public function OwnerBookingById($id);
    public function confirmBooking($id);
    public function cancelBooking($id);
}
