<?php

declare(strict_types=1);

namespace App\BookStore\Infrastructure\Sylius\State\Provider;

use App\BookStore\Application\Query\FindBookQuery;
use App\BookStore\Domain\ValueObject\BookId;
use App\BookStore\Infrastructure\Sylius\Resource\BookResource;
use App\Shared\Application\Query\QueryBusInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ProviderInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @implements ProviderInterface<BookResource>
 */
final readonly class BookBulkItemsProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|array|null
    {
        /** @var string[] $ids */
        $ids = $context->get(RequestOption::class)
            ?->request()
            ->request
            ->all('ids')
        ;

        $resources = [];

        foreach ($ids as $id) {
            $model = $this->queryBus->ask(new FindBookQuery(new BookId(Uuid::fromString($id))));

            $resources[] = BookResource::fromModel($model);
        }

        return $resources;
    }
}