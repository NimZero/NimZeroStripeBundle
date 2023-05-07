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
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Nimzero\StripeBundle\Service\StripeHelper;
use Nimzero\StripeBundle\Twig\StripeTwigExtension;
use Nimzero\StripeBundle\Twig\StripeTwigRuntime;

class NimzeroStripeExtension extends Extension
{
	public function load(array $configs, ContainerBuilder $container)
    {
		$configuration = new Configuration();
		$mergedConfig = $this->processConfiguration($configuration,$configs);

		$stripeHelper = (new Definition(StripeHelper::class))
            ->setPublic(true)
            ->setArgument('$stripe_config', $mergedConfig)
        ;

		$twigExtension = (new Definition(StripeTwigExtension::class))
            ->setPublic(true)
            ->addTag('twig.extension')
        ;

        $twigRuntime = (new Definition(StripeTwigRuntime::class))
            ->setPublic(true)
            ->setAutowired(true)
            ->addTag('twig.runtime')
        ;

        $container->addDefinitions([
			'nimzero_stripe_bundle.stripe_helper' => $stripeHelper,
            'nimzero_stripe_bundle.twig_extension' => $twigExtension,
            'nimzero_stripe_bundle.twig_extension_runtime' => $twigRuntime,
        ]);
	}
}