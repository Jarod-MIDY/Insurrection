<?php

namespace App\Form;

use App\Entity\Game;
use App\Enum\GameRoles;
use App\Enum\GameState;
use App\Records\BadgeSheet;
use App\Records\EchoSheet;
use App\Records\MolotovSheet;
use App\Records\OrderSheet;
use App\Records\PamphletSheet;
use App\Records\PeopleSheet;
use App\Records\PowerSheet;
use App\Records\StarSheet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlayerInfoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['game'] instanceof Game && GameState::LOBBY === $options['game']->getState()) {
            match ($options['data_class']) {
                PowerSheet::class => $this->buildPowerInfoForm($builder, $options),
                OrderSheet::class => $this->buildOrderInfoForm($builder, $options),
                EchoSheet::class => $this->buildEchoInfoForm($builder, $options),
                PeopleSheet::class => $this->buildPeopleInfoForm($builder, $options),
                PamphletSheet::class => $this->buildPamphletInfoForm($builder, $options),
                MolotovSheet::class => $this->buildMolotovInfoForm($builder, $options),
                BadgeSheet::class => $this->buildBadgeInfoForm($builder, $options),
                StarSheet::class => $this->buildStarInfoForm($builder, $options),
                default => throw new \UnexpectedValueException('Unexpected data class'.$options['data_class']),
            };
        }
        $builder
            ->add('notes', TextareaType::class, [
                'label' => 'Notes sur mon Rôle',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'game' => null,
        ]);
    }

    private function buildPowerInfoForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('legitimacy', ChoiceType::class, [
                'label' => 'Votre légitimité est',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICE_LEGITIMACY + [
                    $options['data']->legitimacy => $options['data']->legitimacy,
                ],
            ])
            ->add('importantAgentType', ChoiceType::class, [
                'label' => 'Vos agents importants sont',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICE_AGENT_TYPE + [
                    $options['data']->importantAgentType => $options['data']->importantAgentType,
                ],
            ])
            ->add('blamedFor', ChoiceType::class, [
                'label' => 'On vous reproche surtout de gouverner par',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICE_BLAME + [
                    $options['data']->blamedFor => $options['data']->blamedFor,
                ],
            ])
            ->add('chosenQuestion', ChoiceType::class, [
                'label' => 'Posez UNE question à UNE trajectoire',
                'autocomplete' => true,
                'choices' => [
                    'Pourquoi est-ce que le Pouvoir te considère comme une menace prioritaire ?' => 'Pourquoi est-ce que le Pouvoir te considère comme une menace prioritaire ?',
                    'Pourquoi est-ce que le Pouvoir te considère comme un élément prometteur ?' => 'Pourquoi est-ce que le Pouvoir te considère comme un élément prometteur ?',
                ],
            ])
            ->add('chosenTrajectorie', EnumType::class, [
                'label' => 'Trajectoire choisie',
                'class' => GameRoles::class,
                'autocomplete' => true,
                'choice_label' => function (GameRoles $choice) {
                    return $choice->label();
                },
                'choice_filter' => function (?GameRoles $choice) {
                    return in_array($choice, GameRoles::getTrajectories());
                },
            ])
            ->add('answer', TextType::class, [
                'label' => 'Reponse de la trajectoires',
            ]);
        $builder->get('legitimacy')->resetViewTransformers();
        $builder->get('importantAgentType')->resetViewTransformers();
        $builder->get('blamedFor')->resetViewTransformers();
    }

    private function buildOrderInfoForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fearedBecause', ChoiceType::class, [
                'label' => 'Ce qu\'on craint le plus chez vous, c\'est',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_FEARED_BECAUSE + [
                    $options['data']->fearedBecause => $options['data']->fearedBecause,
                ],
            ])
            ->add('accountableTo', ChoiceType::class, [
                'label' => 'Vous rendez des comptes',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_ACCOUNTABLE_TO + [
                    $options['data']->accountableTo => $options['data']->accountableTo,
                ],
            ])
            ->add('blamedFor', ChoiceType::class, [
                'label' => 'On vous reproche surtout d\'être',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_BLAMED_FOR + [
                    $options['data']->blamedFor => $options['data']->blamedFor,
                ],
            ])
            ->add('chosenQuestion', ChoiceType::class, [
                'label' => 'Posez UNE question à UNE trajectoire',
                'autocomplete' => true,
                'choices' => [
                    'Pourquoi est-ce que l\'Ordre te considère comme une menace prioritaire ?' => 'Pourquoi est-ce que l\'Ordre te considère comme une menace prioritaire ?',
                    'Pourquoi est-ce que l\'Ordre te considère comme un élément prometteur ?' => 'Pourquoi est-ce que l\'Ordre te considère comme un élément prometteur ?',
                ],
            ])
            ->add('chosenTrajectorie', EnumType::class, [
                'class' => GameRoles::class,
                'label' => 'Trajectoire choisie',
                'autocomplete' => true,
                'choice_label' => function (GameRoles $choice) {
                    return $choice->label();
                },
                'choice_filter' => function (?GameRoles $choice) {
                    return in_array($choice, GameRoles::getTrajectories());
                },
            ])
            ->add('answer', TextType::class, [
                'label' => 'Reponse de la trajectoires',
            ]);

        $builder->get('fearedBecause')->resetViewTransformers();
        $builder->get('accountableTo')->resetViewTransformers();
        $builder->get('blamedFor')->resetViewTransformers();
    }

    public function buildEchoInfoForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('financedBy', ChoiceType::class, [
                'label' => 'Vous êtes principalement financés par',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_FINANCED_BY + [
                    $options['data']->financedBy => $options['data']->financedBy,
                ],
            ])
            ->add('importantAgentType', ChoiceType::class, [
                'label' => 'Vos représentants les plus influents sont',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_IMPORTANT_AGENT_TYPE + [
                    $options['data']->importantAgentType => $options['data']->importantAgentType,
                ],
            ])
            ->add('blamedFor', ChoiceType::class, [
                'label' => 'On vous reproche principalement d\'abord d\'être',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_BLAMED_FOR + [
                    $options['data']->blamedFor => $options['data']->blamedFor,
                ],
            ])
            ->add('chosenQuestion', ChoiceType::class, [
                'label' => 'Posez UNE question à UNE trajectoire',
                'autocomplete' => true,
                'choices' => [
                    'Pourquoi est-ce que l\'Écho te considère comme une menace prioritaire ?' => 'Pourquoi est-ce que l\'Écho te considère comme une menace prioritaire ?',
                    'Pourquoi est-ce que l\'Écho te considère comme un élément prometteur ?' => 'Pourquoi est-ce que l\'Écho te considère comme un élément prometteur ?',
                ],
            ])
            ->add('chosenTrajectorie', EnumType::class, [
                'class' => GameRoles::class,
                'label' => 'Trajectoire choisie',
                'autocomplete' => true,
                'choice_label' => function (GameRoles $choice) {
                    return $choice->label();
                },
                'choice_filter' => function (?GameRoles $choice) {
                    return in_array($choice, GameRoles::getTrajectories());
                },
            ])
            ->add('answer', TextType::class, [
                'label' => 'Reponse de la trajectoires',
            ]);
        $builder->get('financedBy')->resetViewTransformers();
        $builder->get('importantAgentType')->resetViewTransformers();
        $builder->get('blamedFor')->resetViewTransformers();
    }

    public function buildPeopleInfoForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('trust', ChoiceType::class, [
                'label' => 'Vous placeriez plutôt votre confiance dans',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_TRUST + [
                    $options['data']->trust => $options['data']->trust,
                ],
            ])
            ->add('priority', ChoiceType::class, [
                'label' => 'Ce qui vous importe le plus, c\'est',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_PRIORITY + [
                    $options['data']->priority => $options['data']->priority,
                ],
            ])
            ->add('blamedFor', ChoiceType::class, [
                'label' => 'On vous reproche avant tout de',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_BLAMED_FOR + [
                    $options['data']->blamedFor => $options['data']->blamedFor,
                ],
            ])
            ->add('chosenQuestion', ChoiceType::class, [
                'label' => 'Posez UNE question à UNE trajectoire',
                'autocomplete' => true,
                'choices' => [
                    'Pourquoi est-ce que le Peuple te considère comme une menace prioritaire ?' => 'Pourquoi est-ce que le Peuple te considère comme une menace prioritaire ?',
                    'Pourquoi est-ce que le Peuple te considère comme un élément prometteur ?' => 'Pourquoi est-ce que le Peuple te considère comme un élément prometteur ?',
                ],
            ])
            ->add('chosenTrajectorie', EnumType::class, [
                'class' => GameRoles::class,
                'label' => 'Trajectoire choisie',
                'autocomplete' => true,
                'choice_label' => function (GameRoles $choice) {
                    return $choice->label();
                },
                'choice_filter' => function (?GameRoles $choice) {
                    return in_array($choice, GameRoles::getTrajectories());
                },
            ])
            ->add('answer', TextType::class, [
                'label' => 'Reponse de la trajectoires',
            ]);
        $builder->get('trust')->resetViewTransformers();
        $builder->get('priority')->resetViewTransformers();
        $builder->get('blamedFor')->resetViewTransformers();
    }

    public function buildTrajectory(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'On t\'appelle',
            ])
            ->add('features', TextareaType::class, [
                'label' => 'Ce qu\'on retient de toi',
            ]);
    }

    public function buildPamphletInfoForm(FormBuilderInterface $builder, array $options): void
    {
        $this->buildTrajectory($builder, $options);
        $builder
            ->add('origins', ChoiceType::class, [
                'label' => 'Tu es d\'origine',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_ORIGINS + [
                    $options['data']->origins => $options['data']->origins,
                ],
            ])
            ->add('modusOperendi', ChoiceType::class, [
                'label' => 'Ton moyen d\'action favori, c\'est',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_MODUS_OPERENDI + [
                    $options['data']->modusOperendi => $options['data']->modusOperendi,
                ],
            ])
            ->add('ROWQuestion', ChoiceType::class, [
                'label' => 'Pose UNE question à l\'Écho ou à l\'Ordre',
                'autocomplete' => true,
                'choices' => [
                    'Pourquoi avez-vous enterré un dossier très compromettant à mon sujet ?' => 'Pourquoi avez-vous enterré un dossier très compromettant à mon sujet ?',
                    'Quelle est la véritable raison qui vous pousse à me mettre sous les projecteurs ?' => 'Quelle est la véritable raison qui vous pousse à me mettre sous les projecteurs ?',
                ],
            ])
            ->add('ROWRole', EnumType::class, [
                'class' => GameRoles::class,
                'label' => 'Emprise choisie',
                'autocomplete' => true,
                'choice_label' => function (GameRoles $choice) {
                    return $choice->label();
                },
                'choice_filter' => function (?GameRoles $choice) {
                    return GameRoles::ECHO === $choice || GameRoles::ORDER === $choice;
                },
            ])
            ->add('ROWAnswer', TextType::class, [
                'label' => 'Reponse de l\'Emprise',
            ])
            ->add('trajQuestion', ChoiceType::class, [
                'label' => 'Posez UNE question à UNE trajectoire',
                'autocomplete' => true,
                'choices' => [
                    'Nous étions meilleur·es ami·es. Qu\'est-ce qui nous a éloignés ?' => 'Nous étions meilleur·es ami·es. Qu\'est-ce qui nous a éloignés ?',
                    'Je n\'ai failli à mon engagement qu\'une seule fois, mais tu étais là. Que s\'est-il passé ?' => 'Je n\'ai failli à mon engagement qu\'une seule fois, mais tu étais là. Que s\'est-il passé ?',
                ],
            ])
            ->add('trajRole', EnumType::class, [
                'class' => GameRoles::class,
                'label' => 'Trajectoire choisie',
                'autocomplete' => true,
                'choice_label' => function (GameRoles $choice) {
                    return $choice->label();
                },
                'choice_filter' => function (?GameRoles $choice) {
                    return in_array($choice, GameRoles::getTrajectories()) && GameRoles::PAMPHLET !== $choice;
                },
            ])
            ->add('trajAnswer', TextType::class, [
                'label' => 'Reponse de la trajectoires',
            ]);
        $builder->get('origins')->resetViewTransformers();
        $builder->get('modusOperendi')->resetViewTransformers();
    }

    public function buildMolotovInfoForm(FormBuilderInterface $builder, array $options): void
    {
        $this->buildTrajectory($builder, $options);
        $builder
            ->add('partOf', ChoiceType::class, [
                'label' => 'Tu fais partie',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_PART_OF + [
                    $options['data']->partOf => $options['data']->partOf,
                ],
            ])
            ->add('dissentReason', ChoiceType::class, [
                'label' => 'Tu es entré en dissidence',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_DISSENT_REASON + [
                    $options['data']->dissentReason => $options['data']->dissentReason,
                ],
            ])
            ->add('ROWQuestion', ChoiceType::class, [
                'label' => 'Pose UNE question à l\'Écho ou au Peuple',
                'autocomplete' => true,
                'choices' => [
                    'Qu\'est-ce que vous ne me pardonnerez jamais ?' => 'Qu\'est-ce que vous ne me pardonnerez jamais ?',
                    'Qu\'est-ce que vous m\'enviez par-dessus tout ?' => 'Qu\'est-ce que vous m\'enviez par-dessus tout ?',
                ],
            ])
            ->add('ROWRole', EnumType::class, [
                'class' => GameRoles::class,
                'label' => 'Emprise choisie',
                'autocomplete' => true,
                'choice_label' => function (GameRoles $choice) {
                    return $choice->label();
                },
                'choice_filter' => function (?GameRoles $choice) {
                    return GameRoles::ECHO === $choice || GameRoles::PEOPLE === $choice;
                },
            ])
            ->add('ROWAnswer', TextType::class, [
                'label' => 'Reponse de l\'Emprise',
            ])
            ->add('trajQuestion', ChoiceType::class, [
                'label' => 'Posez UNE question à UNE trajectoire',
                'autocomplete' => true,
                'choices' => [
                    'Je t\'ai sauvé la vie. Ça s\'est passé comment ?' => 'Je t\'ai sauvé la vie. Ça s\'est passé comment ?',
                    'Qu\'est-ce que nous n\'avons jamais osé nous avouer ?' => 'Qu\'est-ce que nous n\'avons jamais osé nous avouer ?',
                ],
            ])
            ->add('trajRole', EnumType::class, [
                'class' => GameRoles::class,
                'label' => 'Trajectoire choisie',
                'autocomplete' => true,
                'choice_label' => function (GameRoles $choice) {
                    return $choice->label();
                },
                'choice_filter' => function (?GameRoles $choice) {
                    return in_array($choice, GameRoles::getTrajectories()) && GameRoles::MOLOTOV !== $choice;
                },
            ])
            ->add('trajAnswer', TextType::class, [
                'label' => 'Reponse de la trajectoires',
            ]);
        $builder->get('partOf')->resetViewTransformers();
        $builder->get('dissentReason')->resetViewTransformers();
    }

    public function buildBadgeInfoForm(FormBuilderInterface $builder, array $options): void
    {
        $this->buildTrajectory($builder, $options);
        $builder
            ->add('firstRedTapeRisk', ChoiceType::class, [
                'label' => 'Tu as déjà franchi la ligne rouge',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_RED_TAPE + [
                    $options['data']->firstRedTapeRisk => $options['data']->firstRedTapeRisk,
                ],
            ])
            ->add('dissentGoal', ChoiceType::class, [
                'label' => 'Tout ce que tu souhaites, c\'est',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_DISSENT_GOAL + [
                    $options['data']->dissentGoal => $options['data']->dissentGoal,
                ],
            ])
            ->add('ROWQuestion', ChoiceType::class, [
                'label' => 'Pose UNE question à l\'Ordre ou au Pouvoir',
                'autocomplete' => true,
                'choices' => [
                    'Pourquoi n\'agissez-vous pas contre moi ?' => 'Pourquoi n\'agissez-vous pas contre moi ?',
                    'De quelle information cruciale - que vous voudriez dissimuler - ai-je connaissance ?' => 'De quelle information cruciale - que vous voudriez dissimuler - ai-je connaissance ?',
                ],
            ])
            ->add('ROWRole', EnumType::class, [
                'class' => GameRoles::class,
                'label' => 'Emprise choisie',
                'autocomplete' => true,
                'choice_label' => function (GameRoles $choice) {
                    return $choice->label();
                },
                'choice_filter' => function (?GameRoles $choice) {
                    return GameRoles::ORDER === $choice || GameRoles::POWER === $choice;
                },
            ])
            ->add('ROWAnswer', TextType::class, [
                'label' => 'Reponse de l\'Emprise',
            ])
            ->add('trajQuestion', ChoiceType::class, [
                'label' => 'Posez UNE question à UNE trajectoire',
                'autocomplete' => true,
                'choices' => [
                    'Quel surnom d\'informateur·rice me donnes-tu ?' => 'Quel surnom d\'informateur·rice me donnes-tu ?',
                    'Pourquoi est-ce que notre mère te préfère à moi ?' => 'Pourquoi est-ce que notre mère te préfère à moi ?',
                ],
            ])
            ->add('trajRole', EnumType::class, [
                'class' => GameRoles::class,
                'label' => 'Trajectoire choisie',
                'autocomplete' => true,
                'choice_label' => function (GameRoles $choice) {
                    return $choice->label();
                },
                'choice_filter' => function (?GameRoles $choice) {
                    return in_array($choice, GameRoles::getTrajectories()) && GameRoles::BADGE !== $choice;
                },
            ])
            ->add('trajAnswer', TextType::class, [
                'label' => 'Reponse de la trajectoires',
            ]);
        $builder->get('firstRedTapeRisk')->resetViewTransformers();
        $builder->get('dissentGoal')->resetViewTransformers();
    }

    public function buildStarInfoForm(FormBuilderInterface $builder, array $options): void
    {
        $this->buildTrajectory($builder, $options);
        $builder
            ->add('bestQuality', ChoiceType::class, [
                'label' => 'Ta plus grande qualité, c\'est',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_QUALITY + [
                    $options['data']->bestQuality => $options['data']->bestQuality,
                ],
            ])
            ->add('shineIn', ChoiceType::class, [
                'label' => 'Tu brilles',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                ],
                'choices' => $options['data']::CHOICES_SHINE + [
                    $options['data']->shineIn => $options['data']->shineIn,
                ],
            ])
            ->add('ROWQuestion', ChoiceType::class, [
                'label' => 'Pose UNE question au Pouvoir ou au Peuple',
                'autocomplete' => true,
                'choices' => [
                    'Pourquoi est-ce que vous me respectez sincèrement ?' => 'Pourquoi est-ce que vous me respectez sincèrement ?',
                    'Quelle menace justifie que je dispose de votre protection ?' => 'Quelle menace justifie que je dispose de votre protection ?',
                ],
            ])
            ->add('ROWRole', EnumType::class, [
                'class' => GameRoles::class,
                'label' => 'Emprise choisie',
                'autocomplete' => true,
                'choice_label' => function (GameRoles $choice) {
                    return $choice->label();
                },
                'choice_filter' => function (?GameRoles $choice) {
                    return GameRoles::PEOPLE === $choice || GameRoles::POWER === $choice;
                },
            ])
            ->add('ROWAnswer', TextType::class, [
                'label' => 'Reponse de l\'Emprise',
            ])
            ->add('trajQuestion', ChoiceType::class, [
                'label' => 'Posez UNE question à UNE trajectoire',
                'autocomplete' => true,
                'choices' => [
                    'Nous étions rivaux·ales. Comment t\'ai-je mis hors course ?' => 'Nous étions rivaux·ales. Comment t\'ai-je mis hors course ?',
                    'J\'essaye de te contacter sans succès depuis des mois. Pourquoi as-tu enfin accepté ?' => 'J\'essaye de te contacter sans succès depuis des mois. Pourquoi as-tu enfin accepté ?',
                ],
            ])
            ->add('trajRole', EnumType::class, [
                'class' => GameRoles::class,
                'label' => 'Trajectoire choisie',
                'autocomplete' => true,
                'choice_label' => function (GameRoles $choice) {
                    return $choice->label();
                },
                'choice_filter' => function (?GameRoles $choice) {
                    return in_array($choice, GameRoles::getTrajectories()) && GameRoles::STAR !== $choice;
                },
            ])
            ->add('trajAnswer', TextType::class, [
                'label' => 'Reponse de la trajectoires',
            ]);
        $builder->get('bestQuality')->resetViewTransformers();
        $builder->get('shineIn')->resetViewTransformers();
    }
}
