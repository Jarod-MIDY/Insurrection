<?php

namespace App\Form;

use App\Entity\Player;
use App\Entity\SceneLeaderVote;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $vote = $options['data'];
        if (!$vote instanceof SceneLeaderVote) {
            throw new \UnexpectedValueException('Unexpected data class'.$options['data_class']);
        }
        $game = $vote->getPlayer()->getGame();
        $builder
            ->add('votedForPlayer', EntityType::class, [
                'class' => Player::class,
                'choice_label' => 'linkedUser.username',
                'label' => 'Je vote pour ',
                'choices' => $game->getPlayers(),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SceneLeaderVote::class,
        ]);
    }
}
