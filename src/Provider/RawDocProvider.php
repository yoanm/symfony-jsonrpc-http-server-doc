<?php
namespace Yoanm\SymfonyJsonRpcHttpServerDoc\Provider;

use Yoanm\JsonRpcServerDoc\Infra\Normalizer\HttpServerDocNormalizer;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Creator\HttpServerDocCreator;

/**
 * Class RawDocProvider
 */
class RawDocProvider implements DocProviderInterface
{
    /** @var HttpServerDocCreator */
    private $httpServerDocCreator;
    /** @var HttpServerDocNormalizer */
    private $serverDocNormalizer;

    const SUPPORTED_FILENAME = 'raw.json';

    /**
     * @param HttpServerDocCreator    $HttpServerDocCreator
     * @param HttpServerDocNormalizer $serverDocNormalizer
     */
    public function __construct(
        HttpServerDocCreator $HttpServerDocCreator,
        HttpServerDocNormalizer $serverDocNormalizer
    ) {
        $this->httpServerDocCreator = $HttpServerDocCreator;
        $this->serverDocNormalizer = $serverDocNormalizer;
    }

    /**
     * @param string|null $host
     *
     * @return array
     */
    public function getDoc($host = null) : array
    {
        return $this->serverDocNormalizer->normalize(
            $this->httpServerDocCreator->create($host)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports($filename, $host = null) : bool
    {
        return self::SUPPORTED_FILENAME === $filename;
    }
}
