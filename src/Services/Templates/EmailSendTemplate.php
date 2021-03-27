<?php

namespace App\Services\Templates;

use Symfony\Component\Mailer\MailerInterface;

abstract class EmailSendTemplate
{
    public string $userEmail;
    public string $subject;
    public MailerInterface $mailer;

    abstract function send();

    public function __construct(
        MailerInterface $mailer,
        string $userEmail,
        $subject
    ) {
        $this->userEmail = $userEmail;
        $this->subject = $subject;
        $this->mailer = $mailer;
    }
}
