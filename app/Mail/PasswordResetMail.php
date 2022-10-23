<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\PasswordReset;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;

class PasswordResetMail extends Mailable
{
    public function __construct(public PasswordReset $passwordReset)
    {
    }

    public function content()
    {
        return new Content(
            view: 'emails.password-reset',
        );
    }
}
