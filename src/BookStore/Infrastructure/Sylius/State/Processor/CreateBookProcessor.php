<?php

declare(strict_types=1);

namespace App\BookStore\Infrastructure\Sylius\State\Processor;

use App\BookStore\Application\Command\CreateBookCommand;
use App\BookStore\Domain\ValueObject\Author;
use App\BookStore\Domain\ValueObject\BookContent;
use App\BookStore\Domain\ValueObject\BookDescription;
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
final readonly class CreateBookProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    public function process(mixed $data, Operation $operation, Context $context): BookResource
    {
        Assert::isInstanceOf($data, BookResource::class);

        Assert::notNull($data->name);
        Assert::notNull($data->description);
        Assert::notNull($data->author);
        Assert::notNull($data->content);
        Assert::notNull($data->price);

        $command = new CreateBookCommand(
            new BookName($data->name),
            new BookDescription($data->description),
            new Author($data->author),
            new BookContent($data->content),
            new Price($data->price),
        );

        $model = $this->commandBus->dispatch($command);

        return BookResource::fromModel($model);
    }
}
