<?php

namespace App\Contracts;

interface NotificationChannel
{
    public function send(array $data): bool;
    public function supports(string $channel): bool;
} 