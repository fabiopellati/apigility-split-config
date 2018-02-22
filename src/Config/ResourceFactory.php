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

use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\ZendConfigProvider;
use Zend\Stdlib\ArrayUtils;
use ZF\Configuration\ConfigResource;

class ResourceFactory
    extends \ZF\Configuration\ResourceFactory
{

    public function factory($moduleName)
    {

        $moduleName = $this->normalizeModuleName($moduleName);
        if (isset($this->resources[$moduleName])) {
            return $this->resources[$moduleName];
        }
        $moduleConfigPath = $this->modules->getModuleConfigPath($moduleName);
        $config = include $moduleConfigPath;
        $this->mergeAutoloadConfig($config, $moduleConfigPath);
        $this->normalizeConfig($config);
        $this->writer->toFile($moduleConfigPath, $config);
//        $e = new \Exception;
//        var_dump($e->getTraceAsString());
//        print_r([__METHOD__=>__LINE__,$this->fileName]);exit;
        $this->resources[$moduleName] = new ConfigResource($config, $moduleConfigPath, $this->writer);

        return $this->resources[$moduleName];

    }

    protected function mergeAutoloadConfig(&$config, $moduleConfigPath)
    {

        $aggregator = new ConfigAggregator(
            [
                new ZendConfigProvider(dirname($moduleConfigPath) .
                                       '/autoload/apigility-split-config/*.config.php'),
                new ZendConfigProvider(dirname($moduleConfigPath) .
                                       '/autoload/apigility-split-config/**/*.config.php'),
            ]);
        $autoloadConfig = $aggregator->getMergedConfig();
        $config = ArrayUtils::merge($config, $autoloadConfig, true);

    }

    /**
     * @param $config
     */
    protected function normalizeConfig(&$config)
    {
        $this->normalizeZfVersioning($config);
        $this->normalizeRestIndexedArray($config);

    }

    /**
     * @param $config
     */
    protected function normalizeZfVersioning(&$config)
    {
        foreach ($config['zf-versioning']['uri'] as $key => $service) {
            if ($key === $service) {
                continue;
            }
            $config['zf-versioning']['uri'][$service] = $service;
            unset($config['zf-versioning']['uri'][$key]);
        }

    }

    /**
     * @param $config
     */
    protected function normalizeRestIndexedArray(&$config)
    {
        foreach ($config['zf-rest'] as $controller => $controllerConfig) {
            $controllerConfig['entity_http_methods'] = array_unique($controllerConfig['entity_http_methods']);
            $controllerConfig['collection_http_methods'] = array_unique($controllerConfig['collection_http_methods']);
            $controllerConfig['collection_query_whitelist'] = array_unique($controllerConfig['collection_query_whitelist']);
            $config['zf-rest'][$controller] = $controllerConfig;
        }

        foreach ($config['zf-content-negotiation']['accept_whitelist'] as $controller => $controllerConfig) {
            $controllerConfig = array_unique($controllerConfig);
            $config['zf-content-negotiation']['accept_whitelist'][$controller] = $controllerConfig;
        }
        foreach ($config['zf-content-negotiation']['content_type_whitelist'] as $controller => $controllerConfig) {
            $controllerConfig = array_unique($controllerConfig);
            $config['zf-content-negotiation']['content_type_whitelist'][$controller] = $controllerConfig;
        }

    }
}
