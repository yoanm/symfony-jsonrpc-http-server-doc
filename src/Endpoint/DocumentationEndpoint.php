<?php
namespace Yoanm\SymfonyJsonRpcHttpServerDoc\Endpoint;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Yoanm\SymfonyJsonRpcHttpServer\Endpoint\Endpoint;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Provider\ChainNormalizedDocProvider;

/**
 * Class DocumentationEndpoint
 */
class DocumentationEndpoint extends Endpoint
{
    /** @var ChainNormalizedDocProvider */
    private $normalizedDocProvider;

    /**
     * @param ChainNormalizedDocProvider $normalizedDocProvider
     */
    public function __construct(ChainNormalizedDocProvider $normalizedDocProvider)
    {
        parent::__construct([Request::METHOD_GET]);

        $this->normalizedDocProvider = $normalizedDocProvider;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function httpGet(Request $request) : Response
    {
        $filename = $request->get('filename');
        $response = new Response();
        $this->setDefaultResponseHeader($response);

        try {
            $doc = $this->normalizedDocProvider->getFor($filename, $request->getHttpHost());
            $response->setContent(json_encode($doc));
        } catch (\Exception $exception) {
            $response->setContent($exception->getMessage());
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}
