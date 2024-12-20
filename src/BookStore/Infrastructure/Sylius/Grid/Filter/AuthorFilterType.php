<?php

declare(strict_types=1);

namespace App\BookStore\Infrastructure\Sylius\Grid\Filter;

use App\BookStore\Application\Query\FindBooksQuery;
use App\BookStore\Domain\Model\Book;
use App\Shared\Application\Query\QueryBusInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AuthorFilterType extends AbstractType
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'placeholder' => 'sylius.ui.all',
            'choices' => $this->getChoices(),
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    private function getChoices(): array
    {
        $models = $this->queryBus->ask(new FindBooksQuery());

        $choices = [];

        /** @var Book $model */
        foreach ($models as $model) {
            $choices[$model->author()->value] = $model->author()->value;
        }

        ksort($choices);

        return $choices;
    }
}
