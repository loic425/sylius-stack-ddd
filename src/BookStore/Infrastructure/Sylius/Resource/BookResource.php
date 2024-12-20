<?php

declare(strict_types=1);

namespace App\BookStore\Infrastructure\Sylius\Resource;

use ApiPlatform\Metadata\ApiProperty;
use App\BookStore\Domain\Model\Book;
use App\BookStore\Infrastructure\Sylius\Grid\BookGrid;
use App\BookStore\Infrastructure\Sylius\State\Processor\CreateBookProcessor;
use App\BookStore\Infrastructure\Sylius\State\Processor\DeleteBookProcessor;
use App\BookStore\Infrastructure\Sylius\State\Processor\UpdateBookProcessor;
use App\BookStore\Infrastructure\Sylius\State\Provider\BookItemProvider;
use App\BookStore\Infrastructure\Symfony\Form\BookType;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Validator\Constraints as Assert;

#[AsResource(
    section: 'admin',
    formType: BookType::class,
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/admin',
    driver: false,
    operations: [
        new Create(
            processor: CreateBookProcessor::class,
        ),
        new Update(
            provider: BookItemProvider::class,
            processor: UpdateBookProcessor::class,
        ),
        new Delete(
            provider: BookItemProvider::class,
            processor: DeleteBookProcessor::class,
        ),
        new Index(
            grid: BookGrid::class,
        ),
    ],
)]
final class BookResource implements ResourceInterface
{
    public function __construct(
        #[ApiProperty(identifier: true, readable: false, writable: false)]
        public ?AbstractUid $id = null,

        #[Assert\NotNull(groups: ['create'])]
        #[Assert\Length(min: 1, max: 255, groups: ['create', 'Default'])]
        public ?string $name = null,

        #[Assert\NotNull(groups: ['create'])]
        #[Assert\Length(min: 1, max: 1023, groups: ['create', 'Default'])]
        public ?string $description = null,

        #[Assert\NotNull(groups: ['create'])]
        #[Assert\Length(min: 1, max: 255, groups: ['create', 'Default'])]
        public ?string $author = null,

        #[Assert\NotNull(groups: ['create'])]
        #[Assert\Length(min: 1, max: 65535, groups: ['create', 'Default'])]
        public ?string $content = null,

        #[Assert\NotNull(groups: ['create'])]
        #[Assert\PositiveOrZero(groups: ['create', 'Default'])]
        public ?int $price = null,
    ) {
    }

    public function getId(): ?AbstractUid
    {
        return $this->id;
    }

    public static function fromModel(Book $book): self
    {
        return new self(
            $book->id()->value,
            $book->name()->value,
            $book->description()->value,
            $book->author()->value,
            $book->content()->value,
            $book->price()->amount,
        );
    }
}
