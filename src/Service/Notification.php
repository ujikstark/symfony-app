<?php

namespace App\Service;

class Notification {
    
    private $email;

    public function __construct($email)
    {
        dump($email); die;

        $this->email = $email;
    }

    public function sendNotification()
    {

    }
}