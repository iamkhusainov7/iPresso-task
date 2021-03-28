<?php

namespace App\Events\Templates;

use App\Events\Contracts\ObservableInterface;
use InvalidArgumentException;
use App\Listeres\Contracts\ListenerInterface;

abstract class EventTemplate implements ObservableInterface
{
    /**
     * @var ListenerInterface[]
     */
    protected $observers = [];

    public function __construct($observers)
    {
        $this->attach($observers);
    }

    public function attach($listener)
    {
        if (is_array($listener)) {
            foreach ($listener as $observer) {
                if (
                    !$observer instanceof ListenerInterface
                ) {
                    throw new InvalidArgumentException("The listener should be the instance of {ListenerInterface::class}");
                }

                return $this->attach($observer);
            }
        }
        
        $this->observers[] = $listener;
    }

    public function getObservers(): array
    {
        return $this->observers;
    }

    public function detach($index)
    {
        unset($this->observers[$index]);
    }

    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->handle();
        }
    }
}
