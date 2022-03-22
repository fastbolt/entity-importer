<?php

namespace Fastbolt\EntityImporter\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('entity_importer');
        $treeBuilder->getRootNode()
                    ->children()
                    ->scalarNode('default_import_path')
                    ->defaultValue('%kernel.project_dir%/data/import')
                    ->end()
                    ->end();

        return $treeBuilder;
    }
}
