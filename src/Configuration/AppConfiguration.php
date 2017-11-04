<?php

namespace App\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class AppConfiguration implements ConfigurationInterface
{
    const DISTANCE_MIN = 0.0;
    const DISTANCE_MAX = 999.9;

    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('search');

        $rootNode->children()
            ->scalarNode('year')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('type')
            ->end()
            ->scalarNode('ligue')
            ->end()
            ->scalarNode('department')
            ->end()
            ->arrayNode('distance')
                ->isRequired()
                ->children()
                    ->floatNode('min')
                        ->min(self::DISTANCE_MIN)
                        ->max(self::DISTANCE_MAX)
                        ->defaultValue(self::DISTANCE_MIN)
                    ->end()
                    ->floatNode('max')
                        ->min(self::DISTANCE_MIN)
                        ->max(self::DISTANCE_MAX)
                        ->defaultValue(self::DISTANCE_MAX)
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
