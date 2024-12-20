<?php

declare(strict_types=1);

namespace App\BookStore\Infrastructure\Sylius\Grid\Filter;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Filtering\ConfigurableFilterInterface;

final readonly class AuthorFilter implements ConfigurableFilterInterface
{
    public function apply(DataSourceInterface $dataSource, string $name, $data, array $options): void
    {
        throw new \RuntimeException('Not implemented');
    }

    public static function getFormType(): string
    {
        return AuthorFilterType::class;
    }

    public static function getType(): string
    {
        return self::class;
    }
}
