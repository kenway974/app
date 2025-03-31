<?php

namespace App\Form;

use App\Entity\Stat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserStatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stats', EntityType::class, [
                'class' => Stat::class,
                'choices' => $options['stats'], // On passe la liste des Stats non liées
                'choice_label' => 'title', // Affiche le titre des stats
                'multiple' => true, // Permet de sélectionner plusieurs Stats
                'expanded' => true, // Affiche sous forme de checkboxes
            ])
            ->add('save', SubmitType::class, ['label' => 'Ajouter ces Stats']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'stats' => [], // On passe cette option via le contrôleur
        ]);
    }
}
