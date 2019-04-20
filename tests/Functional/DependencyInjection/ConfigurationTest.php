<?php
namespace Tests\Functional\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Yoanm\SymfonyJsonRpcHttpServerDoc\DependencyInjection\Configuration;

/**
 * @covers \Yoanm\SymfonyJsonRpcHttpServerDoc\DependencyInjection\Configuration
 */
class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    protected function getConfiguration()
    {
        return new Configuration();
    }

    public function testShouldHaveADefaultEndpoint()
    {
        $this->assertProcessedConfigurationEquals(
            [[]],
            ['endpoint'=> Configuration::DEFAULT_ENDPOINT],
            'endpoint'
        );
    }
}
