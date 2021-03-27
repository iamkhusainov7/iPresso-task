<?php

use App\Entity\User;
use App\Listeres\Contracts\ListenerInterface;
use Symfony\Component\Mime\Email;
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

        $email = (new Email())
            ->from('no-reply@gmail.com.com')
            ->to($this->user->getEmail())
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Email verification!')
            ->html("<p>Dear user, please confirm your email address by clicking this link: <a href='{$signatureComponents->getSignedUrl()}'>Verify</a></p>");

        $this->mailer->send($email);
    }
}
