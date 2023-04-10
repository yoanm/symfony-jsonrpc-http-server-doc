<?php
namespace DemoApp;

use Symfony\Component\Routing\RouteCollectionBuilder;

class KernelWithMethodDocCreatedListener extends AbstractKernel
{
    /**
     * {@inheritdoc}
     */
    public function getConfigDirectoryName() : string
    {
        return 'config_with_method_doc_created_listener';
    }
}
