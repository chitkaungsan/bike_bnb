<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingComplete extends Notification
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
        ->subject('🌈 Your BikeBnB Ride is Complete! 🚲')
        ->greeting('Sawasdee krub, ' . $name . '! 😄')
        ->line('Your BikeBnB journey has been completed successfully — khob khun krub for riding with us! 🙏💚')
        ->line('We hope your ride was smooth, fun, and full of sabai sabai moments. 🌴✨')
        ->line('Here’s your trip summary:')
        ->line('🔹 **Booking ID:** ' . $this->booking->id)
        ->line('🔹 **Bike:** ' . ($this->booking->bike->title ?? 'N/A'))
        ->line('🔹 **Start Date:** ' . $this->booking->start_date->format('d M Y'))
        ->line('🔹 **End Date:** ' . $this->booking->end_date->format('d M Y'))
        ->line('If you enjoyed your experience, we’d love to see you again for your next adventure! 🌈')
        ->line('Your next sabai sabai ride is just one click away. 🚴‍♂️💨')
        ->salutation('With gratitude and good vibes 💚🌞 — The BikeBnB Team 🌴');
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
