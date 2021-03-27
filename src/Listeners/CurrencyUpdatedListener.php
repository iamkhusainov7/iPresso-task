<?php

use App\Entity\Subscription;
use App\Listeres\Contracts\ListenerInterface;
use App\Services\PlainTextEmailService;
use Symfony\Component\Mailer\MailerInterface;

class CurrencyUpdatedListener implements ListenerInterface
{
    protected $subscription;
    protected $userEmail;
    protected $currentVal;
    public MailerInterface $mailer;

    public function __construct(
        MailerInterface $mailer,
        Subscription $subs,
        string $userEmail,
        float $currentVal
    ) {
        $this->subscription = $subs;
        $this->userEmail = $userEmail;
        $this->currentVal = $currentVal;
        $this->mailer = $mailer;
    }

    public function handle()
    {
        $message = "<p>Dear user, you wanted us to notify you when {$this->subscription->getCurrency()} will be in range {$this->subscription->getMin()} - {$this->subscription->getMin()} pln. 
        Current value is: {$this->currentVal}";
        $email = new PlainTextEmailService(
            $this->mailer,
            $this->userEmail,
            'Currency update',
            $message
        );

        $email->send();
    }
}
