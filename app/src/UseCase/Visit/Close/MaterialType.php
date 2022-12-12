<?php

namespace App\UseCase\Visit\Close;

use App\Entity\Property\Enum\Unit;
use App\ReadModel\MaterialFetcher;
use Doctrine\DBAL\Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterialType extends AbstractType
{
    public function __construct(
        private readonly MaterialFetcher $materialFetcher
    )
    {
    }

    /**
     * @throws Exception
     */
    public function getMaterialChoices(): array
    {
        $choices = [];
        $materials = $this->materialFetcher->findAll();

        foreach ($materials as $material) {
            $choices[$material['name']] = $material['id'];
        }

        return $choices;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('materialId', ChoiceType::class, [
                'label' => 'Материал',
                'choices' => $this->getMaterialChoices()
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Количество'
            ])
            ->add('unit', ChoiceType::class, [
                'label' => 'Единица измерения',
                'choices' => Unit::arrayKeyByKey()
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MaterialCommand::class,
            'label' => 'Блок'
        ]);
    }
}