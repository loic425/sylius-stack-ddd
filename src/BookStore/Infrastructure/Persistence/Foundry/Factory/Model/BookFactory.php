<?php

declare(strict_types=1);

namespace App\BookStore\Infrastructure\Persistence\Foundry\Factory\Model;

use App\BookStore\Domain\Model\Book;
use App\BookStore\Domain\ValueObject\Author;
use App\BookStore\Domain\ValueObject\BookContent;
use App\BookStore\Domain\ValueObject\BookDescription;
use App\BookStore\Domain\ValueObject\BookName;
use App\BookStore\Domain\ValueObject\Price;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Book>
 */
final class BookFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Book::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'author' => new Author(self::faker()->firstName() . ' ' . self::faker()->lastName()),
            'content' => new BookContent(self::faker()->sentence()),
            'description' => new BookDescription(self::faker()->paragraph()),
            'name' => new BookName(ucfirst(self::faker()->words(2, true))),
            'price' => new Price(self::faker()->numberBetween(1, 5000)),
        ];
    }
}
