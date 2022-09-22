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
use Symfony\Bundle\MakerBundle\FileManager;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Util\YamlSourceManipulator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This class adds a maker bundle entry to generate event listener for stripe webhook events.
 * It makes use of the StripeEvent shiped with this bundle.
 * 
 * @since 1.0.0
 * @author TESTA 'NimZero' Charly <contact@nimzero.fr>
 */
class MakeListener extends AbstractMaker
{
  private KernelInterface $kernel;
  private FileManager $fileManager;

  public function __construct(FileManager $fileManager, KernelInterface $kernel)
  {
    $this->fileManager = $fileManager;
    $this->kernel = $kernel;
  }

  public static function getCommandName(): string
  {
    return 'make:nzstripe:listener';
  }

  public static function getCommandDescription(): string
  {
    return 'Creates a new listenen class for Nimzero\StripeBundle';
  }

  public function configureCommand(Command $command, InputConfiguration $inputConfig): void
  {
    $command
      ->addArgument('name', InputArgument::REQUIRED, sprintf('The name of the listener class (e.g. <fg=yellow>%sType</>)', Str::asClassName(Str::getRandomTerm())))
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
      'EventListener\\',
      'EventListener'
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
      "$path/listener/Listener.tpl.php",
      [
        'events' => $events,
        'methods' => $methods
      ]
    );

    $manipulator = new YamlSourceManipulator($this->fileManager->getFileContents('config/services.yaml'));

    /*
    $data = $manipulator->getData();

    $tags = [];

    foreach ($events as $index => $event) {
      $method = $methods[$index];
      $tags[] = "{ name: kernel.event_listener, event: $event, method: $method }";
    }
    
    $data['services'][$subscriberClassNameDetails->getFullName()] = [
      'tags' => $tags
    ];
    
    $manipulator->setData($data);

    $generator->dumpFile('config/services.yaml', $manipulator->getContents());
    */

    $data = $manipulator->getContents();

    $class = $subscriberClassNameDetails->getFullName();

    $data .= <<<EOL

        $class:
            tags:\n
    EOL;

    foreach ($events as $index => $event) {
      $method = $methods[$index];
      $data .= <<<EOL
                  - { name: kernel.event_listener, event: $event, method: $method }\n
      EOL;
    }

    $generator->dumpFile('config/services.yaml', $data);

    $generator->writeChanges();

    $this->writeSuccessMessage($io);
  }

  public function configureDependencies(DependencyBuilder $dependencies): void
  {
  }
}
