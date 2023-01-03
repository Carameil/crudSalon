<?php

namespace App\Admin\Filters;

use App\Entity\Category;
use App\Entity\Position;
use App\ReadModel\CategoryFetcher;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\FilterTrait;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CategoryFilter implements FilterInterface
{
    use FilterTrait;

    public static function new(string $propertyName, array $choices, $label = null): self
    {
        return (new self())
            ->setFilterFqcn(__CLASS__)
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(ChoiceType::class)
            ->setFormTypeOption('mapped', false)
            ->setFormTypeOption('choices', $choices);
    }

    public function apply(
        QueryBuilder  $queryBuilder,
        FilterDataDto $filterDataDto,
        ?FieldDto     $fieldDto,
        EntityDto     $entityDto
    ): void
    {
        $categoryId = $filterDataDto->getValue();

        $queryBuilder
            ->leftJoin(Category::class, 'ct', Join::WITH, 'entity.category = ct.id')
            ->andWhere("ct.id = :categoryId")
            ->setParameter('categoryId', $categoryId);
    }
}