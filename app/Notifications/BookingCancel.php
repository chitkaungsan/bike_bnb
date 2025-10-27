<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingCancel extends Notification
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
            ->subject('💔 Your BikeBnB Booking Has Been Cancelled 😢')
            ->greeting('Sawasdee krub, ' . $name . ' 🙏')
            ->line('We’re truly sorry to let you know that your BikeBnB booking has been cancelled. 😞')
            ->line('Here’s your booking summary:')
            ->line('🔹 **Booking ID:** ' . $this->booking->id)
            ->line('🔹 **Bike:** ' . ($this->booking->bike->title ?? 'N/A'))
            ->line('🔹 **Start Date:** ' . $this->booking->start_date->format('d M Y'))
            ->line('🔹 **End Date:** ' . $this->booking->end_date->format('d M Y'))
            ->line('If this cancellation was unexpected, please contact the owner or our support team. We’ll do our best to help. 🤝')
            ->line('We hope to see you again soon for another sabai sabai ride. 🌴🚲')
            ->salutation('With warm regards and sad hearts 💚😔 — The BikeBnB Team');
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
