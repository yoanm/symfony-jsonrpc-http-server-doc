<?php
namespace Yoanm\SymfonyJsonRpcHttpServerDoc\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 */
class Configuration implements ConfigurationInterface
{
    const DEFAULT_ENDPOINT = '/doc';
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder(JsonRpcHttpServerDocExtension::EXTENSION_IDENTIFIER);

        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->variableNode('endpoint')
                    ->info('Your custom http doc endpoint path')
                    ->treatNullLike(self::DEFAULT_ENDPOINT)
                    ->defaultValue(self::DEFAULT_ENDPOINT)
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
