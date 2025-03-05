<?php

namespace App\Form;

use App\Enum\GameRoles;
use App\Records\RolesSelection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RolesSelectionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('trajectories', EnumType::class, [
            'class' => GameRoles::class,
            'choice_label' => function (GameRoles $choice) {
                return $choice->getDescription();
            },
            'choice_filter' => function (GameRoles $choice) {
                return in_array($choice, GameRoles::getTrajectories());
            },
            'multiple' => true,
            'expanded' => true,
            'required' => false,
        ])
        ->add('rightsOfWay', EnumType::class, [
            'class' => GameRoles::class,
            'choice_label' => function (GameRoles $choice) {
                return $choice->getDescription();
            },
            'choice_filter' => function (GameRoles $choice) {
                return in_array($choice, GameRoles::getRightsOfWay());
            },
            'multiple' => true,
            'expanded' => true,
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RolesSelection::class,
        ]);
    }
}
