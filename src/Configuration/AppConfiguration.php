<?php

namespace App\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class AppConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
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
        ;

        return $treeBuilder;
    }
}
