<?php

declare(strict_types=1);

namespace App\BookStore\Infrastructure\Sylius\State\Processor;

use App\BookStore\Application\Command\UpdateBookCommand;
use App\BookStore\Domain\ValueObject\Author;
use App\BookStore\Domain\ValueObject\BookContent;
use App\BookStore\Domain\ValueObject\BookDescription;
use App\BookStore\Domain\ValueObject\BookId;
use App\BookStore\Domain\ValueObject\BookName;
use App\BookStore\Domain\ValueObject\Price;
use App\BookStore\Infrastructure\Sylius\Resource\BookResource;
use App\Shared\Application\Command\CommandBusInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ProcessorInterface;
use Webmozart\Assert\Assert;

/**
 * @implements ProcessorInterface<BookResource>
 */
final readonly class UpdateBookProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        Assert::isInstanceOf($data, BookResource::class);

        $command = new UpdateBookCommand(
            new BookId($data->id),
            null !== $data->name ? new BookName($data->name) : null,
            null !== $data->description ? new BookDescription($data->description) : null,
            null !== $data->author ? new Author($data->author) : null,
            null !== $data->content ? new BookContent($data->content) : null,
            null !== $data->price ? new Price($data->price) : null,
        );

        $model = $this->commandBus->dispatch($command);

        return BookResource::fromModel($model);
    }
}
