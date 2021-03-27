<?php

use App\Entity\Subscription;
use App\Entity\User;
use App\Listeres\Contracts\ListenerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class CurrencyUpdatedListener implements ListenerInterface
{
    protected $subscription;
    protected $mailer;
    protected $userEmail;
    protected $currentVal;

    public function __construct(
        Subscription $subs,
        string $userEmail,
        float $currentVal,
        MailerInterface $mailer
    ) {
        $this->subscription = $subs;
        $this->mailer = $mailer;
        $this->userEmail = $userEmail;
        $this->currentVal = $currentVal;
    }

    public function handle()
    {
        $email = (new Email())
            ->from('no-reply@gmail.com.com')
            ->to($this->userEmail)
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Email verification!')
            ->html("<p>Dear user, you wanted us to notify you when {$this->subscription->getCurrency()} will be in range {$this->subscription->getMin()} - {$this->subscription->getMin()} pln. Current value is: {$this->currentVal}");

        $this->mailer->send($email);
    }
}
