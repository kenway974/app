<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Stat;
use App\Entity\Task;
use App\Entity\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryTaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('sous_taches')
            ->add('date_butoir', null, [
                'widget' => 'single_text',
            ])
            ->add('importance')
            /*->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])*/
            ->add('stats', EntityType::class, [
                'class' => Stat::class,
                'choices' => $options['stats'], // On passe la liste des Stats non liées
                'choice_label' => 'title', // Affiche le titre des stats
                'multiple' => true, // Permet de sélectionner plusieurs Stats
                'expanded' => true, // Affiche sous forme de checkboxes
            ])
            ->add('checked')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'categories' => [],
            'stats' => [],
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => 'task', 
        ]);
    }
}
