<?php

namespace App\Service;

use App\Entity\User;
use App\Service\Templates\EmailSendTemplate;

class PlainTextEmailService extends EmailSendTemplate
{
    public $text;

    public function __construct(User $user, $subject, $text)
    {
        parent::__construct($user, $subject);

        $this->text = $text;
    }

    public function send()
    {

    }
}