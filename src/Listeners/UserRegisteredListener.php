<?php

use App\Entity\User;
use App\Listeres\Contracts\ListenerInterface;
use App\Services\PlainTextEmailService;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class UserRegisteredListener implements ListenerInterface
{
    protected $user;
    protected $mailer;
    protected $verifyEmailHelper;

    public function __construct(
        User $user,
        VerifyEmailHelperInterface $helper,
        MailerInterface $mailer
    ) {
        $this->user = $user;
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function handle()
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'registration_confirmation_route',
            $this->user->getId(),
            $this->user->getEmail(),
            ['id' => $this->user->getId()]
        );
        $message = "<p>Dear user, please confirm your email address by clicking this link: <a href='{$signatureComponents->getSignedUrl()}'>Verify</a></p>";


        $email = new PlainTextEmailService(
            $this->mailer,
            $this->user->getEmail(),
            'Currency update',
            $message
        );

        $email->send();
    }
}
