<?php

namespace AppBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname')
            ->add("username")
            ->add('password',Repeatedtype::class,[
                'type'=>Passwordtype::class,
                'first_options'=>['label'=>'Password'],
                'second_options'=>['label'=>'Repeat Password'],
                'invalid_message'=>'Les champs mot de passe doivent être identiques',
            ])
            ->add('Save',SubmitType::class)
        ;
    }
}