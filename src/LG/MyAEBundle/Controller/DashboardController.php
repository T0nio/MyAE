<?php

namespace LG\MyAEBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function indexAction()
    {
        $formater = $this->get('lg_my_ae.formater');
        return $this->render("LGMyAEBundle:Dashboard:index.html.twig", array("phone" => $formater->internationalPhoneNumberToFrench("+34663408778")));
    }

    public function menuAction()
    {
        return $this->render("LGMyAEBundle:Client:menu.html.twig");

    }
}