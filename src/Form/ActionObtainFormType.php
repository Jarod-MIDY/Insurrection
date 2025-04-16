<?php

namespace App\Form;

use App\Entity\InfluenceToken;
use App\Entity\TokenAction;
use App\Enum\RolesActionsObtain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActionObtainFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!key_exists('data', $options)) {
            throw new \InvalidArgumentException('Missing tokenAction entity with player data');
        }
        $action = $options['data'];
        if (!$action instanceof TokenAction) {
            throw new \InvalidArgumentException('Unexpected data class'.$options['data_class']);
        }
        $player = $action->getPlayer();
        $playerRole = $player->getRole();
        $choices = $player->getRadianceToken() > 0 ? RolesActionsObtain::getActionsFromRole($playerRole) : [];
        if ([] !== $options['influence_tokens']) {
            foreach ($options['influence_tokens'] as $token) {
                if (!$token instanceof InfluenceToken) {
                    throw new \InvalidArgumentException('Try to add non InfluenceToken entity');
                }
                $tokenRole = $token->getLinkedRole();
                $tokenActions = RolesActionsObtain::getActionsFromRole($tokenRole);
                $choices = array_merge($choices, $tokenActions);
            }
        }
        $builder
            ->add('actionObtain', EnumType::class, [
                'label' => 'Tu peux OBTENIR',
                'class' => RolesActionsObtain::class,
                'autocomplete' => true,
                'options_as_html' => true,
                'choice_label' => fn (RolesActionsObtain $choice): string => '<span class="'.$choice->getRoleFromAction()->value.' game-action">'.$choice->value.'</span>',
                'group_by' => fn (RolesActionsObtain $choice): string => $choice->getRoleFromAction()->label(),
                'choices' => $choices,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TokenAction::class,
            'influence_tokens' => [],
        ]);
    }
}
