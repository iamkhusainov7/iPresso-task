<?php

namespace App\Events\Contracts;

interface ObservableInterface
{
    public function attach($listener);

    public function getObservers(): array;

    public function detach($index);

    public function notify();
}
