<?php

namespace Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

/**
 * Doctrine ORM Provider.
 */
class DoctrineOrmServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['em'] = $app->protect(function () use ($app) {
            $config = Setup::createAnnotationMetadataConfiguration($app['orm.metadata'], APP_DEV);

            return EntityManager::create($app['orm.options']['default'], $config);;
        });
    }
}
