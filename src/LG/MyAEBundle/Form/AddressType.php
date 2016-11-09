<?php

namespace LG\MyAEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AddressType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type',               ChoiceType::class, array(
                'choices'   => array(
                    'Facturation'       => 'Facturation',
                    'Livraison'         => 'Livraison',
                    'Siège'             => 'Siège',
                    'Autre'             => 'Autre',
              )))
            ->add('address',            TextType::class)
            ->add('additonalAddress',   TextType::class, array('required' => false))
            ->add('city',               TextType::class)
            ->add('postalCode',         TextType::class)
            ->add('country',            CountryType::class)
            ->add('name',               TextType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LG\MyAEBundle\Entity\Address'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lg_myaebundle_address';
    }


}
