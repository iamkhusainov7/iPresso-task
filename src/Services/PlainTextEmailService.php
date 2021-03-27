<?php

namespace App\Services;

use App\Services\Templates\EmailSendTemplate;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class PlainTextEmailService extends EmailSendTemplate
{
    public $message;

    public function __construct(
        MailerInterface $mailer,
        string $userEmail,
        string $subject,
        string $message
    ) {
        parent::__construct(
            $mailer,
            $userEmail,
            $subject
        );

        $this->message = $message;
    }

    public function send()
    {
        $email = (new Email())
            ->from('no-reply@gmail.com.com')
            ->to($this->userEmail)
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Email verification!')
            ->html($this->message);

        $this->mailer->send($email);
    }
}
