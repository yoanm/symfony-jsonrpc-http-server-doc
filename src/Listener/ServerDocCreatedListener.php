<?php
namespace Yoanm\SymfonyJsonRpcHttpServerDoc\Listener;

use Yoanm\JsonRpcServer\Domain\Exception\JsonRpcInternalErrorException;
use Yoanm\JsonRpcServer\Domain\Exception\JsonRpcInvalidParamsException;
use Yoanm\JsonRpcServer\Domain\Exception\JsonRpcInvalidRequestException;
use Yoanm\JsonRpcServer\Domain\Exception\JsonRpcMethodNotFoundException;
use Yoanm\JsonRpcServer\Domain\Exception\JsonRpcParseErrorException;
use Yoanm\JsonRpcServerDoc\Domain\Model\ErrorDoc;
use Yoanm\JsonRpcServerDoc\Domain\Model\Type\ArrayDoc;
use Yoanm\JsonRpcServerDoc\Domain\Model\Type\ObjectDoc;
use Yoanm\JsonRpcServerDoc\Domain\Model\Type\StringDoc;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Event\ServerDocCreatedEvent;

/**
 * Class ServerDocCreatedListener
 */
class ServerDocCreatedListener
{
    /**
     * @param ServerDocCreatedEvent $event
     */
    public function appendJsonRpcServerErrorsDoc(ServerDocCreatedEvent $event) : void
    {
        $addParamsValidationError = true;
        // Search for existing error in server errors list (could have been already defined by an another bundle
        // (@see "yoanm/symfony-jsonrpc-params-sf-constraints-doc" package
        foreach($event->getDoc()->getServerErrorList() as $serverError) {
            if (JsonRpcInvalidParamsException::CODE === $serverError->getCode()) {
                $addParamsValidationError = false;
                break;
            }
        }

        $event->getDoc()
            ->addServerError( // Parse Error
                new ErrorDoc('Parse error', JsonRpcParseErrorException::CODE)
            )
            ->addServerError( // Invalid request
                (new ErrorDoc('Invalid request', JsonRpcInvalidRequestException::CODE))
            )
            ->addServerError( // Method not found
                (new ErrorDoc('Method not found', JsonRpcMethodNotFoundException::CODE))
            )
        ;

        if (true === $addParamsValidationError) {
            $event->getDoc()->addServerError( // Params validations error
                (new ErrorDoc('Params validations error', JsonRpcInvalidParamsException::CODE))
                    ->setDataDoc(
                        (new ObjectDoc())
                            ->setAllowMissingSibling(false)
                            ->setAllowExtraSibling(false)
                            ->setRequired(true)
                            ->addSibling(
                                (new ArrayDoc())
                                    ->setName(JsonRpcInvalidParamsException::DATA_VIOLATIONS_KEY)
                            )
                    )
            );
        }

        $event->getDoc()->addServerError( // Internal error
            (new ErrorDoc('Internal error', JsonRpcInternalErrorException::CODE))
                ->setDataDoc(
                    (new ObjectDoc())
                        ->setAllowMissingSibling(false)
                        ->setAllowExtraSibling(false)
                        ->setRequired(false)
                        ->addSibling(
                            (new StringDoc())
                                ->setName(JsonRpcInternalErrorException::DATA_PREVIOUS_KEY)
                                ->setDescription('Previous error message')
                        )
                )
        );
    }
}
