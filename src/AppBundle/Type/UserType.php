<?php

namespace AppBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
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
                'first_options'=>['label'=>'Mot de passe'],
                'second_options'=>['label'=>'Vérification mot de passe'],
                'invalid_message'=>'Les champs mot de passe doivent être identiques',
            ])
            ->add('roles', TextType::class, ['label'=>'Roles (séparés par des virgules'])
            ->add('Save',SubmitType::class)
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesAsArray){
                    //From Model to View -> Array to String
                    return implode(',', $rolesAsArray);
                },
                function ($rolesAsString){
                    //From View to Model -> String to Array
                    return explode(',',$rolesAsString);
                }
            ));
    }
}