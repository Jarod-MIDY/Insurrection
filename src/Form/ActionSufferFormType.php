<?php

namespace App\Form;

use App\Entity\TokenAction;
use App\Enum\RolesActionsSuffer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionSufferFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!key_exists('data', $options)) {
            throw new \InvalidArgumentException('Missing tokenAction entity with player data');
        }
        $action = $options['data'];
        if (!$action instanceof TokenAction) {
            $class = is_object($action) ? $action::class : gettype($action);
            throw new \InvalidArgumentException('Unexpected data class '.$class);
        }
        $playerRole = $action->getPlayer()?->getRole();
        if (null === $playerRole) {
            throw new \InvalidArgumentException('Missing player role');
        }
        $builder
            ->add('actionSuffer', EnumType::class, [
                'label' => 'Tu peux SUBIR',
                'autocomplete' => true,
                'class' => RolesActionsSuffer::class,
                'choice_label' => fn (RolesActionsSuffer $choice): string => $choice->value,
                'choices' => RolesActionsSuffer::getActionsFromRole($playerRole),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TokenAction::class,
        ]);
    }
}
