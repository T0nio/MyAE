<?php
// src/AppBundle/Form/RegistrationType.php

namespace LG\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',          TextType::class)
            ->add('name',               TextType::class)
            ->add('siren',              TextType::class)
            ->add('siret',              TextType::class)
            ->add('address',            TextType::class)
            ->add('postalCode',         TextType::class)
            ->add('city',               TextType::class)
            ->add('country',            CountryType::class)
            ->add('markup',             NumberType::class)
            ->add('banque',             TextType::class)
            ->add('guichet',            TextType::class)
            ->add('compte',             TextType::class)
            ->add('cle',                TextType::class)
            ->add('IBAN',               TextType::class)
            ->remove('username')
            ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    public function getBlockPrefix()
    {
        return 'my_user_profile';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}