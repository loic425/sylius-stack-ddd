<?php

declare(strict_types=1);

namespace App\Tests\BookStore\Functional;

use App\BookStore\Application\Command\DiscountBookCommand;
use App\BookStore\Domain\Repository\BookRepositoryInterface;
use App\BookStore\Domain\ValueObject\BookId;
use App\BookStore\Domain\ValueObject\Price;
use App\Shared\Application\Command\CommandBusInterface;
use App\Tests\BookStore\DummyFactory\DummyBookFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Webmozart\Assert\InvalidArgumentException;

final class DiscountBookTest extends KernelTestCase
{
    protected function setUp(): void
    {
        static::bootKernel();
    }

    /**
     * @dataProvider applyADiscountOnBookDataProvider
     */
    public function testApplyADiscountOnBook(int $initialAmount, int $discount, int $expectedAmount): void
    {
        /** @var BookRepositoryInterface $bookRepository */
        $bookRepository = static::getContainer()->get(BookRepositoryInterface::class);

        /** @var CommandBusInterface $commandBus */
        $commandBus = static::getContainer()->get(CommandBusInterface::class);

        $book = DummyBookFactory::createBook(price: $initialAmount);
        $bookRepository->add($book);

        $commandBus->dispatch(new DiscountBookCommand($book->id, $discount));

        static::assertEquals(new Price($expectedAmount), $bookRepository->ofId($book->id)->price);
    }

    public function applyADiscountOnBookDataProvider(): iterable
    {
        yield [100, 0, 100];
        yield [100, 20, 80];
        yield [50, 30, 35];
        yield [50, 100, 0];
    }

    /**
     * @dataProvider cannotApplyAWrongDiscountDataProvider
     */
    public function testCannotApplyAWrongDiscount(int $discount): void
    {
        static::expectException(InvalidArgumentException::class);

        new DiscountBookCommand(new BookId(), $discount);
    }

    public function cannotApplyAWrongDiscountDataProvider(): iterable
    {
        yield [-10];
        yield [101];
    }
}
