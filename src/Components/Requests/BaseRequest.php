<?php

declare(strict_types=1);

namespace App\Components\Requests;

use Symfony\Component\HttpFoundation\Request;

class BaseRequest extends Request
{
    public function getString(string $key): string
    {
        return (string) $this->get($key);
    }

    public function getBool(string $key): bool
    {
        return (bool) $this->get($key);
    }

    public function getInt(string $key): int
    {
        return (int) $this->get($key);
    }

    public function getFloat(string $key): float
    {
        return (float) $this->get($key);
    }
}
