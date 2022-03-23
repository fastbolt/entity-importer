<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\DependencyInjection;

use Exception;
use Fastbolt\EntityImporter\EntityImporterDefinition;
use Fastbolt\EntityImporter\Factory\SetterDetector;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class EntityImporterExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @return void
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // load service definition
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        // load configuration
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);
        foreach ($config as $key => $value) {
            $container->setParameter('entity_importer.' . $key, $value);
        }

        // register interface usages for tagged auto configuration
        $container->registerForAutoconfiguration(EntityImporterDefinition::class)
                  ->addTag('fastbolt.entity_importer.definition');

        $container->registerForAutoconfiguration(SetterDetector::class)
                  ->addTag('fastbolt.entity_importer.setter_detectors');
    }
}
