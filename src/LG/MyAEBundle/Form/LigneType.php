<?php

namespace LG\MyAEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class LigneType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('type',       ChoiceType::class, array(
            'choices'  => array(
                'Ligne'         => 'default',
                'Titre'           => 'title',
                'Sous-total'        => 'subtotal',
                'Vide'           => 'empty',
            )))
          ->add('title',        TextType::class, array(
              'required' => false,
          ))
          ->add('quantity',     TextType::class, array(
              'required' => false,
          ))
          ->add('pu',           TextType::class, array(
              'required' => false,
          ))
          ->add('remise',       TextType::class, array(
              'required' => false,
          ))
          ->add('subtotals',    TextType::class)
          ->add('ordre',        HiddenType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LG\MyAEBundle\Entity\Ligne'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lg_myaebundle_ligne';
    }
}
