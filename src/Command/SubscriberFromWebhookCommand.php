<?php

/*
 * This file is part of the Stripe Bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\Command;

use Nimzero\StripeBundle\Service\Stripe;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * This command create a event subscriber for all the events of the given webhook
 * 
 * @author TESTA 'NimZero' Charly <contact@nimzero.fr>
 */
class SubscriberFromWebhookCommand extends Command
{
  protected static $defaultName = 'nzstripe:subscriber:from_webhook';
  protected static $defaultDescription = 'This command create a event subscriber for all (not each) the events of the given webhook';

  private Stripe $stripe;

  public function __construct(Stripe $stripe)
  {
    parent::__construct();
    $this->stripe = $stripe;
  }

  protected function configure(): void
  {
    $this->addArgument('webhook', InputArgument::REQUIRED, 'The id of the webhook to create a subscriber for');
  }

  protected function interact(InputInterface $input, OutputInterface $output): void
  {
    if (!$input->getArgument('webhook')) {
      $io = new SymfonyStyle($input, $output);

      $wh = $io->ask('Enter the webhook id', null, [Validator::class, 'notBlank']);
      $input->setArgument('webhook', $wh);
    }
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $io = new SymfonyStyle($input, $output);

    $stripeClient = $this->stripe->getClient();

    /** @var string */
    $whId = $input->getArgument('webhook');

    try {
      $webhook = $stripeClient->webhookEndpoints->retrieve($whId);
    } catch (\Stripe\Exception\ApiErrorException $e) {
      $io->error('Stripe API error: ' . $e->getMessage());
      return Command::FAILURE;
    }

    $events = $webhook->enabled_events;

    // Use the bundle make command 'make:nzstripe:subscriber' to generate the subscriber

    /** @var \Symfony\Component\Console\Application */
    $app = $this->getApplication();
    $command = $app->find('make:nzstripe:subscriber');

    $arguments = [
        'name'    => 'Webhook',
        'event'  => $events,
    ];

    $makerInput = new ArrayInput($arguments);
    $returnCode = $command->run($makerInput, $output);

    $id = $webhook->id;
    $events = join(', ', $events);
    $secret = $webhook->secret;

    if ($returnCode === Command::SUCCESS) {
      $io->success([
        "Generated subscriber for webhook: $id",
        "Webhook secret for config: $secret",
        "Events: $events",
      ]);
  
      return Command::SUCCESS;
    }
    
    return Command::FAILURE;
  }
}
