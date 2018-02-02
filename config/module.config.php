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
namespace ApigilitySplitConfig;
return [
    'apigility-split-config' => [

    ],
    'service_manager' => [
        'factories' => [
            'ZF\Configuration\ConfigWriter'=>\ApigilitySplitConfig\Config\Writer\ConfigWriterFactory::class,
            'ZF\Configuration\ConfigResourceFactory' => \ApigilitySplitConfig\Config\ResourceFactoryFactory::class
        ],
    ],
];
