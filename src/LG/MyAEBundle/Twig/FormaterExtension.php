<?php

namespace LG\MyAEBundle\Twig;

use LG\MyAEBundle\Services\Formater\LGFormater;

class FormaterExtension extends \Twig_Extension
{
  /**
   * @var LGFormater
   */
  private $lgFormater;

  public function __construct(LGFormater $lgFormater)
  {
    $this->lgFormater = $lgFormater;
  }

  public function formatArgumentToInternationalPhoneNumberToFrench($number)
  {
    return $this->lgFormater->internationalPhoneNumberToFrench($number);
  }

  public function getFunctions()
  {
    return array(
      new \Twig_SimpleFunction('formatFrenchNumber', array($this, 'formatArgumentToInternationalPhoneNumberToFrench')),
    );
  }

  // La méthode getName() identifie votre extension Twig, elle est obligatoire
  public function getName()
  {
    return 'LGFormater';
  }
}