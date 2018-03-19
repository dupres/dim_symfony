<?php
/**
 * Created by PhpStorm.
 * User: digital
 * Date: 05/02/2018
 * Time: 16:12
 */

namespace AppBundle\Type;

use AppBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class ShowType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['required'=>'false'])
            ->add('category',EntityType::class, [
                'class'=> Category::Class,
                'choice_label'=>'name',
            ])
            ->add('abstract')
            ->add('country', CountryType::class)
            //->add('author')
            ->add('releasedDate', DateType::class)
            ->add('tmpPicture', FileType::class);
    }
}