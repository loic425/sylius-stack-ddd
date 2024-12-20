<?php

declare(strict_types=1);

namespace App\BookStore\Application\Query;

use App\BookStore\Domain\Repository\BookRepositoryInterface;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<BookRepositoryInterface>
 */
final readonly class FindAuthorsQuery implements QueryInterface
{
}
