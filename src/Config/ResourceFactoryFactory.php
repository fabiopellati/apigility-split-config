<?php
/**
 *
 * apigility-tools (https://github.com/fabiopellati/apigility-tools)
 *
 * @link      https://github.com/fabiopellati/apigility-tools for the canonical source repository
 * @copyright Copyright (c) 2017 Fabio Pellati (https://github.com/fabiopellati)
 * @license   http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 *
 */

namespace ApigilitySplitConfig\Config;

use Interop\Container\ContainerInterface;
use ZF\Configuration\ModuleUtils;

class ResourceFactoryFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ResourceFactory
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container)
    {
        return new ResourceFactory(
            $container->get(ModuleUtils::class),
            $container->get('ZF\\Configuration\\ConfigWriter')
        );
    }
}
