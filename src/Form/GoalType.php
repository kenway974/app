<?php

namespace App\Form;

use App\Entity\Goal;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choices' => $options['categories'], // On passe la liste des Stats non liées
                'choice_label' => 'title', // Affiche le titre des stats
                'expanded' => true, // Affiche sous forme de checkboxes
            ])
            ->add('description')
            ->add('dateButoir', null, [
                'widget' => 'single_text',
            ])
            ->add('pourquoi')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Goal::class,
            'categories' => [],
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => 'goal', 
        ]);
    }
}
