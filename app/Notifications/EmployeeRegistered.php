<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeRegistered extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $token = auth()->tokenById($notifiable->user->id);
        return (new MailMessage)
                    ->subject("Greetings! Welcome to Loakarya Indonesia's Team")
                    ->line('Welcome to our company, ' . $notifiable->user->first_name . " " . $notifiable->user->last_name . '.')
                    ->line('We hope you will enjoy our journey together.')
                    ->action('Please click here to set your password.', env('EMPLOYEE_SET_PASSWORD_LINK') . "?token=" . $token)
                    ->line('The link above is only valid for 24 hours. Please set your password immidiately.')
                    ->line('To log in to the staff portal, please use your company email (' . $notifiable->user->email . ')')
                    ->line('Thank you for choosing us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
