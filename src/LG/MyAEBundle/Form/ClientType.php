<?php

namespace LG\MyAEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use LG\MyAEBundle\Form\AddressType;

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name',         TextType::class)
          ->add('referentName', TextType::class)
          ->add('status',       ChoiceType::class, array(
            'choices'  => array(
                'Prospect'              => 'Prospect',
                'Prêt à signer'         => 'Prêt à signer',
                'Client régulier'       => 'Client régulier',
                'Fin de relation'       => 'Fin de relation',
                'Autre'                 => 'Autre',
            )))
          ->add('dateActif',    DateType::class)
          ->add('mail',         EmailType::class)
          ->add('telephone',    TextType::class)
          ->add('addresses', CollectionType::class, array(
            'entry_type'   => AddressType::class,
            'allow_add'    => true,
            'allow_delete' => true
          ))
          ->add('save',         SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LG\MyAEBundle\Entity\Client'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lg_myaebundle_client';
    }
}
