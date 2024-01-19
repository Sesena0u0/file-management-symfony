<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints as Assert;

class SigninFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                "attr" => [
                    'class' => 'form-control form-control-lg'
                ],

                'label' => 'Email',
                'label_attr' => [
                    'class' => 'text-white',
                ],

                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email()
                ]

            ])

            ->add('password', RepeatedType::class, [

                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Password',
                    'label_attr' => [
                        'class' => 'text-white form-label',
                    ],
                    'attr' => [
                        'class' => 'form-control form-control-lg mb-3',
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirm Password',
                    'label_attr' => [
                        'class' => 'text-white form-label',
                    ],
                    'attr' => [
                        'class' => 'form-control form-control-lg',
                    ]
                ],

                'invalid_message' => 'Password doesn\'t match'

            ])

            ->add('Name', TextType::class, [
                "attr" => [
                    'class'=> 'form-control form-control-lg',
                    'minlength' => "2",
                    'maxlength' => "20"
                ],
                
                'label' => 'Name',
                'label_attr' => [
                    'class' => 'text-white form-label',
                ],

                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => '2', 'max' => '20'])
                ]

            ])

            ->add('Signin', SubmitType::class, [
                "attr" => [
                    'class'=> 'btn btn-primary btn-lg',
                ],

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
