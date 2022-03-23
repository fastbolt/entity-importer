<?php

/**
 * Copyright © Fastbolt Schraubengroßhandels GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fastbolt\EntityImporter\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @psalm-suppress MixedMethodCall
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('entity_importer');
        $treeBuilder->getRootNode()
                    ->children()
                    ->scalarNode('default_import_path')
                    ->defaultValue('%kernel.project_dir%/data/import')
                    ->end()
                    ->scalarNode('processed_path')
                    ->defaultValue('%kernel.project_dir%/data/import/processed')
                    ->end()
                    ->scalarNode('archive_filename_date_format')
                    ->defaultValue('Y-m-d\TH-i-s')
                    ->end()
                    ->end();

        return $treeBuilder;
    }
}
