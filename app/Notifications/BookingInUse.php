<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingInUse extends Notification
{
    use Queueable;

    public $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $name = property_exists($notifiable, 'name')
            ? $notifiable->name
            : ($this->booking->name ?? 'Guest');

        return (new MailMessage)
    ->subject('🚲 Your BikeBnB Ride Starts Today! 🌞')
    ->greeting('Sawasdee krub, ' . $name . '! 😄')
    ->line('It’s time! Your BikeBnB booking starts today — get ready to ride freely and feel the wind! 🌴💨')
    ->line('Here’s your booking summary:')
    ->line('🔹 **Booking ID:** ' . $this->booking->id)
    ->line('🔹 **Bike:** ' . ($this->booking->bike->title ?? 'N/A'))
    ->line('🔹 **Start Date:** ' . $this->booking->start_date->format('d M Y'))
    ->line('🔹 **End Date:** ' . $this->booking->end_date->format('d M Y'))
    ->line('Make sure your bike is ready and ride safely. 🛞💚')
    ->action('🚲 View My Booking', url('/bookings/success/' . $this->booking->id))
    ->line('Thank you for choosing BikeBnB — may your journey be smooth and sabai sabai all the way! 🌈')
    ->salutation('Enjoy your ride! 😎 — The BikeBnB Team 🌴');
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
