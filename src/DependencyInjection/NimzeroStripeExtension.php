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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class NimzeroStripeExtension extends Extension
{
	public function load(array $configs, ContainerBuilder $container)
    {
        // Load the config files
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../config')
        );

        // Apply the services
        $loader->load('services.yaml');

		$configuration = new Configuration();
		$mergedConfig = $this->processConfiguration($configuration,$configs);

        // Configure services with their parameters        
        $stripe = $container->getDefinition('nimzero_stripe_bundle.stripe_helper');
        $stripe->addArgument('$stripe_config', $mergedConfig);
	}
}