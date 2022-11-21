<?php

namespace App\Admin\Filters;

use App\Admin\Filters\Types\FullNameType;
use App\Entity\User\User;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\FilterTrait;

class FullNameFilter implements FilterInterface
{
    use FilterTrait;

    public static function new(string $propertyName, $label = null): self
    {
        return (new self())
            ->setFilterFqcn(__CLASS__)
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(FullNameType::class)
            ->setFormTypeOption('mapped', false);
    }

    public function apply(
        QueryBuilder  $queryBuilder,
        FilterDataDto $filterDataDto,
        ?FieldDto     $fieldDto,
        EntityDto     $entityDto
    ): void
    {
        $splitFilter = $filterDataDto->getValue();
        unset($splitFilter['comparison']);
        $fullName = rtrim(implode(' ', $splitFilter));

        $queryBuilder
            ->AndWhere("CONCAT_WS(' ', entity.firstName, entity.lastName, entity.middleName) LIKE :fullName")
            ->setParameter('fullName', '%' . $fullName . '%');
    }
}