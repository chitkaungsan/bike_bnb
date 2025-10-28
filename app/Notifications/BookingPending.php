<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Booking;

class BookingPending extends Notification
{
    use Queueable;

    public $booking;
    public $storeName;

    public function __construct(Booking $booking, $storeName = null)
    {
        $this->booking = $booking;
        $this->storeName = $storeName;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $bike = $this->booking->bike;
        $riderName = $this->booking->name ?? 'A rider';
        $start = $this->booking->start_date->format('d M Y');
        $end = $this->booking->end_date->format('d M Y');
        $total = number_format($this->booking->total_price, 2);
        $payment = ucfirst($this->booking->payment_type);
        $store = $this->storeName ?? 'Unknown Store';

        return (new MailMessage)
            ->subject('🌴 Woohoo! New Booking from ' . $store . ' on BikeBnB! 🚲✨')
            ->greeting('Hey hey, ' . ($notifiable->name ?? 'Bike Owner') . '! 😎')
            ->line('Good news! You’ve got a fresh new booking from **' . $store . '**! 🎉')
            ->line('Time to get your bike ready for another sabai sabai adventure! 🌞')
            ->line('')
            ->line('Here’s the ride info:')
            ->line('🔹 **Bike:** ' . ($bike->title ?? 'Unknown Bike'))
            ->line('🔹 **Rider Name:** ' . $riderName)
            ->line('🔹 **Booking ID:** #' . $this->booking->id)
            ->line('🔹 **Start Date:** ' . $start)
            ->line('🔹 **End Date:** ' . $end)
            ->line('🔹 **Payment Type:** ' . $payment)
            ->line('🔹 **Total Price:** ฿' . $total)
            ->line('')
            ->action('🚲 View Booking Now', url('/owner/bookings/' . $this->booking->id))
            ->line('Hop on your dashboard and confirm this ride before someone else does! 😁')
            ->line('Let’s make sure every ride feels sabai sabai for both you and your rider! 🌈')
            ->salutation('Big smiles, 🌴 The BikeBnB Team 💚');
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
