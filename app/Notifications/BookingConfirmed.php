<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;
class BookingConfirmed extends Notification
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
        ->subject('🎉 Your BikeBnB Booking Has Been Confirmed! 🚲')
        ->greeting('Sawasdee krub, ' . $name . '! 😄')
        ->line('Good news! Your BikeBnB booking has been confirmed by the owner. 💚')
        ->line('Here’s your booking summary:')
        ->line('🔹 **Booking ID:** ' . $this->booking->id)
        ->line('🔹 **Bike:** ' . ($this->booking->bike->title ?? 'N/A'))
        ->line('🔹 **Start Date:** ' . $this->booking->start_date->format('d M Y'))
        ->line('🔹 **End Date:** ' . $this->booking->end_date->format('d M Y'))
        ->line('🏝️ Get ready to ride freely and enjoy your trip!')
        ->line('Thank you for choosing BikeBnB — where every ride feels sabai sabai! 🌞')
        ->salutation('With smiles, 😎 BikeBnB Team 🌴');

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
