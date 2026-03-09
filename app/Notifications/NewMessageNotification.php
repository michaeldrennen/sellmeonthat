<?php

namespace App\Notifications;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Message $message,
        public Conversation $conversation
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
        $senderName = $this->message->sender->name;
        $wantTitle = $this->conversation->want->title;

        return (new MailMessage)
            ->subject('New Message from ' . $senderName)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($senderName . ' sent you a message about "' . $wantTitle . '"')
            ->line('"' . \Illuminate\Support\Str::limit($this->message->body, 150) . '"')
            ->action('View Conversation', route('conversations.show', $this->conversation))
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
            'message_id' => $this->message->id,
            'conversation_id' => $this->conversation->id,
            'sender_name' => $this->message->sender->name,
            'want_title' => $this->conversation->want->title,
            'message_preview' => \Illuminate\Support\Str::limit($this->message->body, 100),
        ];
    }
}
