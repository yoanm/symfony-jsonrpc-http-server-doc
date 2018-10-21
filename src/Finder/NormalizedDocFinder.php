<?php
namespace Yoanm\SymfonyJsonRpcHttpServerDoc\Finder;

use Yoanm\SymfonyJsonRpcHttpServerDoc\Provider\DocProviderInterface;

/**
 * Class NormalizedDocFinder
 */
class NormalizedDocFinder
{
    /** @var DocProviderInterface[] */
    private $normalizedDocProviderList = [];

    /**
     * @param DocProviderInterface $provider
     */
    public function addProvider(DocProviderInterface $provider)
    {
        $this->normalizedDocProviderList[] = $provider;
    }

    /**
     * @param string      $filename
     * @param string|null $host
     *
     * @return array
     *
     * @throws \Exception In case no provider found
     */
    public function findFor(string $filename, $host) : array
    {
        foreach ($this->normalizedDocProviderList as $provider) {
            if (true === $provider->supports($filename, $host)) {
                return $provider->getDoc($host);
            }
        }

        throw new \Exception(sprintf('No documentation provider found for "%s"/"%s"', $filename, $host));
    }
}
