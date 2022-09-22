<?php

/*
 * This file is part of the Stripe Bundle.
 *
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('nimzero_stripe');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('stripe')
                    ->children()
                        ->scalarNode('api_version')
                            ->defaultNull()
                            ->isRequired()
                        ->end() // api_version
                        ->scalarNode('api_secret_key')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end() // api_secret_key
                        ->scalarNode('api_public_key')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end() // api_public_key
                        ->scalarNode('webhook_secret')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end() // webhook_secret
                        ->integerNode('tolerance')
                            ->defaultValue(300)
                        ->end() // tolerance
                    ->end()
                ->end() // stripe
            ->end()
        ;

        return $treeBuilder;
    }
}