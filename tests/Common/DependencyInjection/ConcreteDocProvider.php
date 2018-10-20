<?php

namespace Tests\Common\DependencyInjection;

use Yoanm\SymfonyJsonRpcHttpServerDoc\Provider\DocProviderInterface;

class ConcreteDocProvider implements DocProviderInterface
{
    public function getDoc($host = null) : array
    {
        return [];
    }

    public function supports($filename, $host = null) : bool
    {
        return true;
    }
}
