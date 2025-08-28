<?php

namespace App\Form;

use App\Entity\InfluenceToken;
use App\Entity\Player;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendInfluenceTokenFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (key_exists('data', $options) && !$options['data'] instanceof InfluenceToken) {
            throw new \InvalidArgumentException('Missing Data in SendInfluenceTokenFormType');
        }
        /**
         * @var InfluenceToken $token
         */
        $token = $options['data'];
        $sender = $token->getSender();
        if (null === $sender) {
            throw new \InvalidArgumentException('influence token sender can\'t be null');
        }
        $choices = $sender->getGame()?->getPlayers()->filter(function (Player $player) use ($sender): bool {
            return $player !== $sender;
        });
        $builder
            ->add('receiver', EntityType::class, [
                'class' => Player::class,
                'choices' => $choices ?? [],
                'autocomplete' => true,
                'choice_label' => 'linkedUser.username',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InfluenceToken::class,
        ]);
    }
}
