<?php

/*
 * This file is part of nimzero/stripe-bundle.
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
	public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('nimzero_stripe');

        $treeBuilder->getRootNode()
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('api_version')
                    ->info('Used to configure the Stripe version used by the StripeClient, if null use your Stripe account default version')
                    ->defaultNull()
                    ->validate()
                        ->ifTrue(function ($v) {return !is_null($v) && !$this->validateDate($v);})
                        ->thenInvalid('The api version should be in the YYYY-MM-DD format')
                    ->end()
                ->end() // api_version
                ->arrayNode('modes')
                    ->children()
                        ->arrayNode('live')
                            ->canBeDisabled()
                            ->children()
                                ->scalarNode('public_key')
                                ->end() // public key
                                ->scalarNode('secret_key')
                                ->end() // secret key
                                ->scalarNode('webhook_secret')
                                ->end() // webhook secret
                            ->end()
                        ->end() // live
                        ->arrayNode('test')
                            ->canBeDisabled()
                            ->children()
                                ->scalarNode('public_key')
                                ->end() // public key
                                ->scalarNode('secret_key')
                                ->end() // secret key
                                ->scalarNode('webhook_secret')
                                ->end() // webhook secret
                            ->end()
                        ->end() // test
                    ->end()
                ->end() // modes
                ->integerNode('tolerance')
                    ->info('The tolerance is the allowed difference in seconds between the current timestamp and the timestamp in the request header')
                    ->defaultValue(300)
                    ->min(1)
                ->end() // tolerance
            ->end()
        ;

        return $treeBuilder;
	}

    function validateDate($date, $format = 'Y-m-d'){
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}