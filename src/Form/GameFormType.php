<?php

namespace App\Form;

use App\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class GameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la session',
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                ],
            ])
            ->add('subject', TextareaType::class, [
                'label' => 'Explication du Thème',
            ])
            ->add('thingsToTalkAbout', TextareaType::class, [
                'label' => 'Sujets à Aborder',
            ])
            ->add('thingsToHalfTalk', TextareaType::class, [
                'label' => 'Sujets Voilés',
            ])
            ->add('banedTopics', TextareaType::class, [
                'label' => 'Sujets Bannis',
            ])
            ->add('password', TextType::class, [
                'label' => 'Mot de Passe',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
