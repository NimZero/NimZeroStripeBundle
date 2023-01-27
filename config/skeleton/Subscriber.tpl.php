<?= "<?php\n" ?>

namespace <?= $namespace; ?>;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
<?php if ($doctrine) : ?>
use Doctrine\Persistence\ManagerRegistry;
<?php endif ?>
use Nimzero\StripeBundle\Event\StripeEvent;

class <?= $class_name ?> implements EventSubscriberInterface
{
<?php if ($doctrine) : ?>
    public function __construct(
        private ManagerRegistry $doctrine
    )
    {}
<?php endif ?>

    public static function getSubscribedEvents(): array
    {
        return [
<?php foreach ($events as $index => $event): ?>
            '<?= $event ?>' => [
                ['<?= $methods[$index]; ?>', 0]
            ],
<?php endforeach ?>
        ];
    }

<?php foreach ($methods as $method) : ?>
    public function <?= $method ?>(StripeEvent $event): void
    {
        // ...
    }

<?php endforeach ?>
}