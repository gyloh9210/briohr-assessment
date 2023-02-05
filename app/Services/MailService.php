<?php

namespace App\Services;

use App\Mail\EmailNotification;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function sendEmail(string $email, string $content, string $subject)
    {
        Mail::to($email)
            ->send(
                new EmailNotification(
                    $content,
                    $subject
                )
            );
    }
}
