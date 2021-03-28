<?php

namespace App\Events;

use App\Events\Contracts\ObservableInterface;
use App\Events\Templates\EventTemplate;

class UserRegistratedEvent extends EventTemplate implements ObservableInterface
{
}
