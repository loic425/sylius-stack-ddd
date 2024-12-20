<?php

namespace App\BookStore\Infrastructure\Sylius\Grid;

use App\BookStore\Application\Query\FindBooksQuery;
use App\BookStore\Domain\ValueObject\Author;
use App\BookStore\Infrastructure\Sylius\Resource\BookResource;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Infrastructure\Sylius\Grid\GridPageResolver;
use Pagerfanta\Adapter\FixedAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\PagerfantaInterface;
use Sylius\Component\Grid\Data\DataProviderInterface;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;

final readonly class BookGridProvider implements DataProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function getData(Grid $grid, Parameters $parameters): PagerfantaInterface
    {
        /** @var array<string, string> $criteria */
        $criteria = $parameters->get('criteria', []);

        /** @var array<string, string> $sorting */
        $sorting = $parameters->get('sorting') ?? $grid->getSorting();

        $models = $this->queryBus->ask(new FindBooksQuery(
            author: !empty($criteria['author'] ?? null) ? new Author($criteria['author']) : null,
            page: GridPageResolver::getCurrentPage($grid, $parameters),
            itemsPerPage: GridPageResolver::getItemsPerPage($grid, $parameters),
            alphabeticalSortingAsc: 'asc' === ($sorting['name'] ?? null) ? true : null,
            alphabeticalSortingDesc: 'desc' === ($sorting['name'] ?? null) ? true : null,
        ));

        $data = [];
        foreach ($models as $model) {
            $data[] = BookResource::fromModel($model);
        }

        return new Pagerfanta(new FixedAdapter(count($models), $data));
    }
}
