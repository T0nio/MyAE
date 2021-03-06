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

class FactureType extends AbstractType
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
              'query_builder' => function ($er) use ($options){
                return $er->createQueryBuilder('w')
                          ->where('w.ownedBy = :username')
                          ->setParameter('username', $options['user']);
              }
          ))       

          ->add('type',               ChoiceType::class, array(
                'choices'   => array(
                    'Facture'       => 'Facture',
                    'Accompte'         => 'Accompte',
              )))
          ->add('paiement_date' ,   DateType::class)
          ->add('prestation_date' , DateType::class)
          ->add('penalite_date' ,   DateType::class)
          ->add('penalite_taux',    TextType::class)
          ->add('accompte',         TextType::class)
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
        $resolver->setDefined('user');
        $resolver->setDefaults(array(
            'data_class' => 'LG\MyAEBundle\Entity\Facture',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lg_myaebundle_facture';
    }
}
