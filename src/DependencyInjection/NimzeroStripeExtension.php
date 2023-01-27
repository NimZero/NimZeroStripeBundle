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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class NimzeroStripeExtension extends Extension
{
	public function load(array $configs, ContainerBuilder $container)
    {
		$configuration = new Configuration();
		$mergedConfig = $this->processConfiguration($configuration,$configs);

		$loader = new YamlFileLoader(
			$container,
			new FileLocator(__DIR__.'/../../config')
		);

		$loader->load('services.yaml');

		// Configure services with their parameters        
        $stripeHelperService = $container->getDefinition('nimzero_stripe_bundle.stripe_helper');
        $stripeHelperService->replaceArgument('$stripe_config', $mergedConfig);
	}
}