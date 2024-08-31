<?php

namespace Majesty\Framework\Controller;

interface AbstractControllerInterface
{
    public static function getInstance(): object;
    public function support(string $subclass): bool;
}