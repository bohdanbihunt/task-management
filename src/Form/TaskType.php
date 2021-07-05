<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Task;
use App\Entity\User;

class TaskType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder->add('title', null, [
            'label' => 'Title',
            'constraints' => [
                new NotBlank([
                    'message' => 'Please fill in the field'
                ])
            ]
        ]);
        
        $builder->add('description', null, [
            'label' => 'Description'
        ]);
        
        $builder->add('deadline', DateType::class, [
            'label' => 'Deadline',
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'placeholder' => [
                'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
            ]
        ]);
        
        $builder->add('user', EntityType::class, array(
            'label' => 'User',
            'class' => User::class,
            'placeholder' => 'Select user',
            'multiple' => false,
            'required' => true,
            'choice_label' => function (User $user) {
                return $user->getUsername();
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                          ->orderBy('u.username', 'asc');
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }

}
