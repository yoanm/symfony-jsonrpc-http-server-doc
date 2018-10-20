<?php
namespace Yoanm\SymfonyJsonRpcHttpServerDoc\Endpoint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Provider\ChainNormalizedDocProvider;

/**
 * Class DocumentationEndpoint
 */
class DocumentationEndpoint
{
    /** @var ChainNormalizedDocProvider */
    private $normalizedDocProvider;

    /** @var string[] */
    private $allowedMethodList = [];

    /**
     * @param ChainNormalizedDocProvider $normalizedDocProvider
     */
    public function __construct(ChainNormalizedDocProvider $normalizedDocProvider)
    {
        $this->normalizedDocProvider = $normalizedDocProvider;
        $this->allowedMethodList = [Request::METHOD_GET, Request::METHOD_OPTIONS];
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function httpOptions(Request $request) : Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        // Set allowed http methods
        $response->headers->set('Allow', $this->allowedMethodList);
        $response->headers->set('Access-Control-Request-Method', $this->allowedMethodList);

        // Set allowed content type
        $response->headers->set('Accept', 'application/json');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');

        return $response;
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
        $response->headers->set('Content-Type', 'application/json');

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
