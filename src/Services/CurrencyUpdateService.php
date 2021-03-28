<?php

namespace App\Services;

use App\ApiProviders\CurrencySource;
use App\Entity\Subscription;
use App\Events\CurrencyUpdatedEvent;
use CurrencyUpdatedListener;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;

final class CheckCurrencyUpdateService
{
    private function __construct()
    {
        //
    }

    /**
     * Checks for updates of currencies and notifies users
     * 
     * @param EntityManagerInterface
     * 
     * @return void
     */
    public static function check(
        EntityManagerInterface $manager,
        MailerInterface $mailer
    ): void {
        $currency = new CurrencySource();
        $currencyResponse = $currency->get('/exchangerates/tables/a');
        $rates = self::normalizeRates($currencyResponse);
        $manager = $manager->getRepository(Subscription::class);
        $subscriptions = $manager->getAll();
        $mustNotify = [];
        $ids = [];

        foreach ($subscriptions as $subscription) {
            $tempKey = strtolower($subscription[0]->getCurrency());

            if (!isset($rates[$tempKey])) {
                continue;
            }

            if (
                $rates[$tempKey] >= $subscription[0]->getMin() &&
                $rates[$tempKey] <= $subscription[0]->getMax()
            ) {
                $ids[] = $subscription[0]->getId();
                $mustNotify[] = new CurrencyUpdatedListener(
                    $mailer,
                    $subscription[0],
                    $subscription['email'],
                    $rates[$tempKey],
                );
            }
        };

        if (
            !$mustNotify
        ) {
            return;
        }

        (new CurrencyUpdatedEvent($mustNotify))->notify();
        $manager->updateSubscriptions($ids);
    }

    /**
     * Makes a proper structure of rates to be easier retreived the currencies
     * 
     * @param array $rates - the array of currencies with its rate
     * 
     * @return array
     */
    protected static function normalizeRates(array $rates): array
    {
        $result = [];

        foreach ($rates as $rate) {
            $key = strtolower($rate['code']);

            $result[$key] = $rate['mid'];
        }

        return $result;
    }
}
