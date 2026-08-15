<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;



class VerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        // Generate the secure, timed verification URL
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );

	

        // Render the email using a clean Markdown structure
        return (new MailMessage)
            ->subject('Activate Your Account')
            ->markdown('emails.verify-email', [
                'url' => $verificationUrl,
                'name' => trim(($notifiable->first_name ?? '') . ' ' . ($notifiable->last_name ?? '')),
            ]);
    }
}

