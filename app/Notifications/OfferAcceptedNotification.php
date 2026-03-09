<?php

namespace App\Notifications;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfferAcceptedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Offer $offer
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $userName = $this->offer->want->user->name;
        $wantTitle = $this->offer->want->title;
        $price = number_format($this->offer->price, 2);

        return (new MailMessage)
            ->subject('Your Offer Has Been Accepted!')
            ->greeting('Congratulations!')
            ->line($userName . ' has accepted your offer on "' . $wantTitle . '"')
            ->line('Accepted Price: $' . $price)
            ->line('You can now contact them to finalize the details.')
            ->action('View Want', route('wants.show', $this->offer->want))
            ->line('Thank you for using SellMeOnThat!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'offer_id' => $this->offer->id,
            'want_id' => $this->offer->want_id,
            'user_name' => $this->offer->want->user->name,
            'want_title' => $this->offer->want->title,
            'price' => $this->offer->price,
        ];
    }
}
