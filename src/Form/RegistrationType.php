<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr' => [
                    'placeholder' => 'Prénom'
                ],
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom'
                ],
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Email'
                ],
                'required' => true
            ])
            // ->add('password', PasswordType::class, [
            //     'attr' => [
            //         'placeholder' => 'Mot de passe'
            //     ],
            //     'required' => true
            // ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'required' => true,
                'first_options'  => [
                    'attr' => [
                        'placeholder' => 'Mot de passe'
                    ],
                    'label' => false
                ],
                'second_options' => [
                    'attr' => [
                        'placeholder' => 'Confirmer le mot de passe'
                    ],
                    'label' => false
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
