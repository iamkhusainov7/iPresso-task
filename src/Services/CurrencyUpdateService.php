<?php

namespace App\Service;

use App\ApiProviders\CurrencySource;
use App\Entity\Subscription;
use App\Events\CurrencyUpdatedEvent;
use CurrencyUpdatedListener;
use Doctrine\ORM\EntityManagerInterface;

final class CheckCurrencyUpdateService
{
    private function __construct()
    {
        //
    }

    public static function check(
        EntityManagerInterface $manager,
        $mailer
    ) {
        $currency = new CurrencySource();
        $currencyResponse = $currency->get('/exchangerates/tables/a');
        $rates = self::normalizeRates($currencyResponse);
        $manager = $manager->getRepository(Subscription::class);
        $subscriptions = $manager->getAll();
        $mustNotify = [];
        $ids = [];

        foreach ($subscriptions as $subscription) {
            $tempKey = $subscription[0]->getCurrency();

            if (
                $rates[$tempKey] >= $subscription[0]->getMin() &&
                $rates[$tempKey] <= $subscription[0]->getMax()
            ) {
                $ids[] = $subscription[0]->getId();
                $mustNotify[] = new CurrencyUpdatedListener(
                    $subscription[0],
                    $subscription['email'],
                    $rates[$tempKey],
                    $mailer
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

    protected static function normalizeRates(array $rates)
    {
        $result = [];

        foreach ($rates as $rate) {
            $key = $rate['code'];

            $result[$key] = $rate['mid'];
        }

        return $result;
    }
}
