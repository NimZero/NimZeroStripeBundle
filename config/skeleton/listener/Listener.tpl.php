<?= "<?php\n"; ?>

namespace <?= $namespace; ?>;

use Nimzero\StripeBundle\Event\StripeEvent;
<?php if ($doctrine) : ?>
use Doctrine\Persistence\ManagerRegistry;
<?php endif ?>

class <?= "$class_name\n"; ?>
{
<?php if ($doctrine) : ?>
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
      $this->doctrine = $doctrine;
    }
<?php endif ?>

<?php foreach ($methods as $method) : ?>
    public function <?= $method ?>(StripeEvent $event): void
    {
        // ...
    }

<?php endforeach ?>
}