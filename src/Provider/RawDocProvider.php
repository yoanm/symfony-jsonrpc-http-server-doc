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
    private $HttpServerDocCreator;
    /** @var HttpServerDocNormalizer */
    private $serverDocNormalizer;

    /**
     * @param HttpServerDocCreator    $HttpServerDocCreator
     * @param HttpServerDocNormalizer $serverDocNormalizer
     */
    public function __construct(HttpServerDocCreator $HttpServerDocCreator, HttpServerDocNormalizer $serverDocNormalizer)
    {
        $this->HttpServerDocCreator = $HttpServerDocCreator;
        $this->serverDocNormalizer = $serverDocNormalizer;
    }

    /**
     * @param string|null $host
     *
     * @return array
     */
    public function getDoc($host = null)
    {
        return $this->serverDocNormalizer->normalize(
            $this->HttpServerDocCreator->create($host)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports($filename, $host = null)
    {
        return 'raw.json' === $filename;
    }
}
