<?php
namespace LG\MyAEBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FactureEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {

  }

  public function getParent()
  {
    return FactureType::class;
  }
}
