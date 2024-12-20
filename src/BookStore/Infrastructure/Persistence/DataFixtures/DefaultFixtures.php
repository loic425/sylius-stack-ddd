<?php

declare(strict_types=1);

namespace App\BookStore\Infrastructure\Persistence\DataFixtures;

use App\BookStore\Infrastructure\Persistence\Foundry\Story\DefaultLibraryStory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DefaultFixtures extends Fixture
{
    #[\Override]
    public function load(ObjectManager $manager): void
    {
        DefaultLibraryStory::load();
    }
}
