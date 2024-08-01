<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                "attr" => [
                    'hidden'=> 'true',
                    'class'=> 'import-file',
                    'onchange'=> "$('.send-file').click();",
                ],
                'label_attr' => [
                    'hidden' => 'true',
                ],

            ])
            ->add('Envoyer', SubmitType::class, [
                "attr" => [
                    'hidden'=> 'true',
                    'class'=> 'send-file',
                    'onclick'=>'window.location.href=""'
                ],

            ])
        ;

        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
