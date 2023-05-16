<?php

/*
 * This file is part of nimzero/stripe-bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Maker;

use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;

class MakeSubscriber extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:nzstripe:subscriber';
    }

    public static function getCommandDescription(): string
    {
      return 'Creates a new subscriber class for Nimzero\StripeBundle Events';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command
            ->addArgument('name', InputArgument::OPTIONAL, sprintf('The name of the subscriber class (e.g. <fg=yellow>%sType</>)', Str::asClassName(Str::getRandomTerm())))
            ->addOption('doctrine', 'd', InputOption::VALUE_NONE, 'Add "Doctrine\Persistence\ManagerRegistry" as a "doctrine" attribut in your subscriber')
            ->addArgument('events', InputArgument::IS_ARRAY, 'The name(s) of the events(s) (e.g. <fg=yellow>customer.subscription.created</>)')
        ;

        $inputConfig->setArgumentAsNonInteractive('events');
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {}

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        if (!$input->getArgument('events')) {
            $selectedEvents = [];
            $reflexion = new \ReflectionClass(\Stripe\Event::class);
            $events = $reflexion->getConstants();
            unset($events['OBJECT_NAME']);
            $events = array_values($events);

            do {
                $question = new Question(sprintf(' <fg=green>%s</>', 'Add a event to the subscriber (press <return> to stop adding event)'));
                $question->setAutocompleterValues($events);

                $last = $io->askQuestion($question);

                if (null !== $last) {
                    unset($events[array_search($last, $events)]);
                    $selectedEvents[] = $last;
                }
            } while (count($selectedEvents) < 1 || null !== $last);

            $input->setArgument('events', $selectedEvents);
        }
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        /** @var string */
        $name = $input->getArgument('name');

        /** @var bool */
        $doctrine = $input->getOption('doctrine');

        /** @var string[] */
        $events = $input->getArgument('events');

        $subscriberClassNameDetails = $generator->createClassNameDetails(
            $name,
            'EventSubscriber\\',
            'EventSubscriber'
        );

        $methods = [];
        foreach ($events as $index => $event) {
            $event = "stripe_bundle.$event";
            $events[$index] = $event;
            $methods[$index] = Str::asEventMethod($event);
        }

        $generator->generateClass(
            $subscriberClassNameDetails->getFullName(),
            __DIR__."/../../config/skeleton/Subscriber.tpl.php",
            [
              'doctrine' => $doctrine,
              'events' => $events,
              'methods' => $methods,
            ]
        );
      
        $generator->writeChanges();
    
        $this->writeSuccessMessage($io);
    }
}