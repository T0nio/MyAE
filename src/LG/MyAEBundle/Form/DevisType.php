<?php

namespace LG\MyAEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use LG\MyAEBundle\Form\AddressType;
use LG\MyAEBundle\Form\LigneType;
use LG\MyAEBundle\Form\ClientType;

class DevisType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('client', EntityType::class, array(
              'class' => 'LGMyAEBundle:Client',
              'choice_label' => 'name',
          ))
          ->add('title',        TextType::class)
          ->add('description',  TextareaType::class)
          ->add('validity',     TextType::class)
          ->add('markup',       TextType::class)
          ->add('remisable',    CheckboxType::class, array(
              'label'    => " ",
              'required' => false,
          ))
          ->add('lignes',       CollectionType::class, array(
            'entry_type'   =>   LigneType::class,
            'allow_add'    =>   true,
            'allow_delete' =>   true
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
            'data_class' => 'LG\MyAEBundle\Entity\Devis'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lg_myaebundle_devis';
    }
}
