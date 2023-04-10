<?php
namespace DemoApp;

use Symfony\Component\Routing\RouteCollectionBuilder;

class DefaultKernel extends AbstractKernel
{
    public function getConfigDirectoryName() : string
    {
        return 'default_config';
    }
}
