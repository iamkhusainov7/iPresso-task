<?php

namespace App\Services\Templates;

use Symfony\Component\Mailer\MailerInterface;

abstract class EmailSendTemplate
{
    /**
     * @var string user email
     */
    public string $userEmail;

    /**
     * @var string subject of the email
     */
    public string $subject;

    /**
     * @var MailerInterface
     */
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
