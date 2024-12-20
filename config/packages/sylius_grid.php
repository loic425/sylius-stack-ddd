<?php

declare(strict_types=1);

use App\BookStore\Infrastructure\Sylius\Grid\Filter\AuthorFilter;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('sylius_grid', [
        'templates' => [
            'filter' => [
                AuthorFilter::class => '@SyliusBootstrapAdminUi/shared/grid/filter/select.html.twig',
            ],
        ],
    ]);
};
