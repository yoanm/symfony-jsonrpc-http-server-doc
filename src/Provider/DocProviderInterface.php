<?php
namespace Yoanm\SymfonyJsonRpcHttpServerDoc\Provider;

/**
 * Interface DocProviderInterface
 */
interface DocProviderInterface
{
    /**
     * @param string|null $host
     *
     * @return array
     */
    public function getDoc($host = null);

    /**
     * $param string      $filename
     * @param string|null $host
     *
     * @return bool
     */
    public function supports($filename, $host = null);
}
