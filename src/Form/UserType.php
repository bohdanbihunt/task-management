<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
    
class UserType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder->add('username', null, [
            'label' => 'Username',
            'constraints' => [
                new NotBlank([
                    'message' => 'Please fill in the field'
                ])
            ]
        ]);
        
        $builder->add('email', null, [
            'label' => 'Email',
            'constraints' => [
                new NotBlank([
                    'message' => 'Please fill in the field'
                ]),
                new Email([
                    'message' => 'Incorrect email address'
                ])
            ]
        ]);
        
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();

            if (!$user || null === $user->getId()) {
                $form->add('plainPassword', TextType::class, [
                    'label' => 'Password',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please fill in the field'
                        ])
                    ]
                ]);
            } else {
                $form->add('plainPassword', TextType::class, [
                    'label' => 'Password'
                ]);
            }
        });
    }
    
    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
