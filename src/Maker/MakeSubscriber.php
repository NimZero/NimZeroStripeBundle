<?php

/*
 * This file is part of the Stripe Bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class MakeSubscriber extends AbstractMaker
{
  private KernelInterface $kernel;

  public function __construct(KernelInterface $kernel)
  {
    $this->kernel = $kernel;
  }

  public static function getCommandName(): string
  {
    return 'make:nzstripe:subscriber';
  }

  public static function getCommandDescription(): string
  {
    return 'Creates a new subscriber class for Nimzero\StripeBundle';
  }

  public function configureCommand(Command $command, InputConfiguration $inputConfig): void
  {
    $command
      ->addArgument('name', InputArgument::REQUIRED, sprintf('The name of the subscriber class (e.g. <fg=yellow>%sType</>)', Str::asClassName(Str::getRandomTerm())))
      ->addArgument('event', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name(s) of the evnts(s) (e.g. <fg=yellow>customer.subscription.created</>)');

      $inputConfig->setArgumentAsNonInteractive('event');
  }

  public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
  {
  }

  public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
  {
    /** @var string */
    $name = $input->getArgument('name');

    $subscriberClassNameDetails = $generator->createClassNameDetails(
      $name,
      'EventSubscriber\\',
      'EventSubscriber'
    );

    /** @var string[] */
    $events = $input->getArgument('event');

    $methods = [];
    foreach ($events as $index => $event) {
      $event = "nimzero.stripe_bundle.$event";
      $events[$index] = $event;
      $methods[$index] = Str::asEventMethod($event);
    }

    $path = $this->kernel->locateResource('@NimzeroStripeBundle/config/skeleton/');

    $generator->generateClass(
      $subscriberClassNameDetails->getFullName(),
      "$path/subscriber/Subscriber.tpl.php",
      [
        'events' => $events,
        'methods' => $methods
      ]
    );

    $generator->writeChanges();

    $this->writeSuccessMessage($io);
  }

  public function configureDependencies(DependencyBuilder $dependencies): void
  {
  }
}
