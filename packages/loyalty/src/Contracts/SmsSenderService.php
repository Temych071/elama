<?php

namespace Loyalty\Contracts;

interface SmsSenderService
{
    public function send(string $phone, string $message): void;
}
