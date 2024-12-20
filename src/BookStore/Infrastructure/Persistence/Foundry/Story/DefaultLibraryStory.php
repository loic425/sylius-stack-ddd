<?php

declare(strict_types=1);

namespace App\BookStore\Infrastructure\Persistence\Foundry\Story;

use App\BookStore\Infrastructure\Persistence\Foundry\Factory\Model\BookFactory;
use Zenstruck\Foundry\Story;

final class DefaultLibraryStory extends Story
{
    public function build(): void
    {
        BookFactory::createMany(100);
    }
}
