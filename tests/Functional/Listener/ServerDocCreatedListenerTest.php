<?php
namespace Tests\Functional\Listener;

use PHPUnit\Framework\TestCase;
use Yoanm\JsonRpcServer\Domain\Exception\JsonRpcInternalErrorException;
use Yoanm\JsonRpcServer\Domain\Exception\JsonRpcInvalidParamsException;
use Yoanm\JsonRpcServer\Domain\Exception\JsonRpcInvalidRequestException;
use Yoanm\JsonRpcServer\Domain\Exception\JsonRpcMethodNotFoundException;
use Yoanm\JsonRpcServer\Domain\Exception\JsonRpcParseErrorException;
use Yoanm\JsonRpcServerDoc\Domain\Model\ErrorDoc;
use Yoanm\JsonRpcServerDoc\Domain\Model\ServerDoc;
use Yoanm\JsonRpcServerDoc\Domain\Model\Type\ArrayDoc;
use Yoanm\JsonRpcServerDoc\Domain\Model\Type\ObjectDoc;
use Yoanm\JsonRpcServerDoc\Domain\Model\Type\StringDoc;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Event\ServerDocCreatedEvent;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Listener\ServerDocCreatedListener;

/**
 * @covers \Yoanm\SymfonyJsonRpcHttpServerDoc\Listener\ServerDocCreatedListener
 */
class ServerDocCreatedListenerTest extends TestCase
{
    /** @var ServerDocCreatedListener */
    private $listener;

    protected function setUp(): void
    {
        $this->listener = new ServerDocCreatedListener();
    }

    public function testShouldAddTheFiveCommonErrorsByDefault()
    {
        $serverDoc = new ServerDoc();
        $event = new ServerDocCreatedEvent($serverDoc);

        $this->listener->appendJsonRpcServerErrorsDoc($event);

        $serverErrorList = $serverDoc->getServerErrorList();

        $this->assertCount(5, $serverErrorList);

        $parseError = $invalidRequestError = $methodNotFoundError = $paramsValidationError = $internalError = null;

        foreach ($serverErrorList as $serverError) {
            switch ($serverError->getCode()) {
                case JsonRpcParseErrorException::CODE:
                    $parseError = $serverError;
                    break;
                case JsonRpcInvalidRequestException::CODE:
                    $invalidRequestError = $serverError;
                    break;
                case JsonRpcMethodNotFoundException::CODE:
                    $methodNotFoundError = $serverError;
                    break;
                case JsonRpcInvalidParamsException::CODE:
                    $paramsValidationError = $serverError;
                    break;
                case JsonRpcInternalErrorException::CODE:
                    $internalError = $serverError;
                    break;
                default:
                    throw new \Exception(
                        sprintf(
                            'Unhandled exception code "%s" ("%s")',
                            $serverError->getCode(),
                            get_class($serverError)
                        )
                    );
            }
        }

        $this->assertNotNull($parseError);
        $this->assertNotNull($invalidRequestError);
        $this->assertNotNull($methodNotFoundError);
        $this->assertNotNull($paramsValidationError);
        $this->assertNotNull($internalError);
    }

    public function testInternalErrorShouldHaveRightDataDoc()
    {
        $serverDoc = new ServerDoc();
        $event = new ServerDocCreatedEvent($serverDoc);

        $this->listener->appendJsonRpcServerErrorsDoc($event);

        $serverErrorList = $serverDoc->getServerErrorList();

        $internalError = null;

        foreach ($serverErrorList as $serverError) {
            switch ($serverError->getCode()) {
                case JsonRpcInternalErrorException::CODE:
                    $internalError = $serverError;
                    break;
            }
        }

        $this->assertNotNull($internalError->getDataDoc());
        $this->assertEquals(
            (new ObjectDoc())
                ->setAllowMissingSibling(false)
                ->setAllowExtraSibling(false)
                ->setRequired(false)
                ->addSibling(
                    (new StringDoc())
                        ->setName(JsonRpcInternalErrorException::DATA_PREVIOUS_KEY)
                        ->setDescription('Previous error message')
                ),
            $internalError->getDataDoc()
        );
    }

    public function testParamValidationErrorShouldHaveADefaultDataDoc()
    {
        $serverDoc = new ServerDoc();
        $event = new ServerDocCreatedEvent($serverDoc);

        $this->listener->appendJsonRpcServerErrorsDoc($event);

        $serverErrorList = $serverDoc->getServerErrorList();

        $paramsValidationError = null;

        foreach ($serverErrorList as $serverError) {
            switch ($serverError->getCode()) {
                case JsonRpcInvalidParamsException::CODE:
                    $paramsValidationError = $serverError;
                    break;
            }
        }

        $this->assertNotNull($paramsValidationError->getDataDoc());
        $this->assertEquals(
            (new ObjectDoc())
                ->setAllowMissingSibling(false)
                ->setAllowExtraSibling(false)
                ->setRequired(true)
                ->addSibling(
                    (new ArrayDoc())
                        ->setName(JsonRpcInvalidParamsException::DATA_VIOLATIONS_KEY)
                ),
            $paramsValidationError->getDataDoc()
        );
    }

    public function testParamValidationShouldNotBeAddedIfAlreadyExisting()
    {
        $serverDoc = new ServerDoc();
        $event = new ServerDocCreatedEvent($serverDoc);
        $myCustomParamsValidationErrors = new ErrorDoc(
            'My custom params validations error',
            JsonRpcInvalidParamsException::CODE
        );

        $serverDoc->addServerError($myCustomParamsValidationErrors);

        $this->listener->appendJsonRpcServerErrorsDoc($event);

        $serverErrorList = $serverDoc->getServerErrorList();

        $paramsValidationError = null;

        foreach ($serverErrorList as $serverError) {
            switch ($serverError->getCode()) {
                case JsonRpcInvalidParamsException::CODE:
                    $paramsValidationError = $serverError;
                    break;
            }
        }

        $this->assertSame($myCustomParamsValidationErrors, $paramsValidationError);
    }
}
