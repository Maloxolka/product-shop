<?php

use App\Components\Requests\BaseRequest;
use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

Request::setFactory(function () {
    return new BaseRequest(...func_get_args());
});

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
