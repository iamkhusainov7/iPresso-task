<?php

namespace App\Service\Templates;

use App\Entity\User;

abstract class EmailSendTemplate
{
    public $user;
    public $subject;

    abstract function send();

    public function __construct(User $user, $subject)
    {
        $this->user = $user;
        $this->subject = $subject;
    }
}
