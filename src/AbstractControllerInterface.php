<?php

namespace Spacers\Framework\Controller;

interface AbstractControllerInterface
{
    public static function getInstance(): object;
    public function support(string $subclass): bool;
}