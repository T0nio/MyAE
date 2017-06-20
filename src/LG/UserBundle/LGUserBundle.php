<?php

namespace LG\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;



class LGUserBundle extends Bundle
{
   public function getParent()
  {
    return 'FOSUserBundle';
  }
}
