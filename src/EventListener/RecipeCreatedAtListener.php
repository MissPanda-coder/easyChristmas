<?php
namespace App\EventListener;

use App\Entity\Recipe;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use DateTimeImmutable;

class RecipeCreatedAtListener
{
    public function prePersist(Recipe $recipe, LifecycleEventArgs $event): void
    {
        if ($recipe->getCreatedAt() === null) {
            $recipe->setCreatedAt(new DateTimeImmutable());
        }
    }
}
