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

use Nimzero\StripeBundle\Service\StripeHelper;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\EventRegistry;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MakeWebhookFromEvents extends AbstractMaker
{
    public function __construct(
        private StripeHelper $stripeHelper,
        private UrlGeneratorInterface $urlGenerator,
        private EventRegistry $eventRegistry,
    ) {
    }

    public static function getCommandName(): string
    {
        return 'make:nzstripe:webhook';
    }

    public static function getCommandDescription(): string
    {
      return 'This command create webhook for all the subscribed/listened Stripe events from the bundle';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->addArgument('mode', InputArgument::OPTIONAL, 'The Stripe mode in wich to create the webhook: live / test')
            ->addOption('connect', 'c', InputOption::VALUE_NONE, 'Listen to connected accounts')
            ->addOption('show-secret', 's', InputOption::VALUE_NONE, 'Display the webhook signing secret in the console')
        ;

        $inputConfig->setArgumentAsNonInteractive('mode');
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {}

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        if (!$input->getArgument('mode')) {

            $question = new ChoiceQuestion(
                sprintf(' <fg=green>%s</>', 'In wich mode do you want to create the webhook:'),
                ['test', 'live'],
                'test'
            );

            $mode = $io->askQuestion($question);
            $input->setArgument('mode', $mode);
        }
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): int
    {
        $events = $this->eventRegistry->getAllActiveEvents();
        
        $events = array_filter($events, function ($v) {
            return str_starts_with($v, 'stripe_bundle.');
        });

        $final = [];

        foreach ($events as $value) {
            $final[] = str_replace('stripe_bundle.', '', $value);
        }

        $io->writeln('The command will generate a webhook with all the following events');
        $io->listing($final);
        
        $question = new ConfirmationQuestion('Create webhook ?', false);
        $proceed = $io->askQuestion($question);

        if (false === $proceed) {
            return Command::FAILURE;
        }

        $mode = $input->getArgument('mode');
        $live = 'live' === $mode;
        $client = $this->stripeHelper->getClient(live: $live);

        $opt = [
            'enabled_events' => $final,
            'url' => $this->urlGenerator->generate('nimzero.stripe_bundle.stripe.webhook_endpoint', ['mode' => $mode], UrlGeneratorInterface::ABSOLUTE_URL),
            'api_version' => $this->stripeHelper->getApiVersion(),
            'description' => 'Generate by nzstripe:create:webhook',
        ];

        if ($input->getOption('connect')) {
            $opt['connect'] = true;
        }

        try {
            $wh = $client->webhookEndpoints->create($opt);
        } catch (\Stripe\Exception\ApiErrorException $th) {
            $io->error($th->getMessage());
            return Command::FAILURE;
        }

        $io->success('Webhook created, add it\'s signing secret to your configuration');

        if ($input->getOption('show-secret')) {
            $io->info(sprintf('Signing secret: %s', $wh->secret));
        }

        return Command::SUCCESS;
    }
}
