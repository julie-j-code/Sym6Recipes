<?php

namespace App\Form;

use App\Entity\Recipes;
use App\Entity\Ingredients;
use Symfony\Component\Form\AbstractType;
use App\Repository\IngredientsRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RecipesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name')
            ->add('time')
            ->add('nbPeople')
            ->add(
                'difficulty',
                RangeType::class,
                [
                    'attr' => [
                        'class' => 'form-range',
                        'min' => 1,
                        'max' => 5
                    ],
                    'required' => false,
                    'label' => 'Difficulté'
                ]
            )
            ->add('description')
            ->add('price', MoneyType::class, [
                    'label' => 'Prix ',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]]
)
            // ->add('createdAt')
            // ->add('updatedAt')
            // ->add('isFavorite')
            ->add('ingredients', EntityType::class, [
                'class' => Ingredients::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name',
                'label' => 'Les ingrédients',
                'attr' => ['class' => 'd-flex flex-wrap gap-3']
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Créer une recette'
            ]);
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipes::class,
        ]);
    }
}
