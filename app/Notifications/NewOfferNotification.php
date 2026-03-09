<?php

namespace App\Notifications;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOfferNotification extends Notification implements ShouldQueue
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
        $businessName = $this->offer->businessProfile->business_name;
        $wantTitle = $this->offer->want->title;
        $price = number_format($this->offer->price, 2);

        return (new MailMessage)
            ->subject('New Offer from ' . $businessName)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($businessName . ' has made an offer on your want: "' . $wantTitle . '"')
            ->line('Offered Price: $' . $price)
            ->when($this->offer->message, function ($mail) {
                return $mail->line('Message: "' . $this->offer->message . '"');
            })
            ->action('View Offer', route('wants.show', $this->offer->want))
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
            'business_name' => $this->offer->businessProfile->business_name,
            'want_title' => $this->offer->want->title,
            'price' => $this->offer->price,
            'message' => $this->offer->message,
        ];
    }
}
