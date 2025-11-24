<?php

declare(strict_types=1);

namespace Theodo\AccentBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class TheodoAccentBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        if (!interface_exists('ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface')) {
            throw new \LogicException(
                'API Platform is required to use TheodoAccentBundle. ' .
                'Please install either "api-platform/core" or "api-platform/symfony". ' .
                'Run: composer require api-platform/symfony'
            );
        }
    }
}
