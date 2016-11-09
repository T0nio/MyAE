<?php

namespace LG\MyAEBundle\Controller;

use LG\MyAEBundle\Entity\Client;
use LG\MyAEBundle\Entity\Address;
use LG\MyAEBundle\Entity\Devis;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use LG\MyAEBundle\Form\DevisType;
use LG\MyAEBundle\Form\DevisEditType;

class DevisController extends Controller
{
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $devisRepository = $em->getRepository('LGMyAEBundle:Devis');
        $devis = $devisRepository->findBy(array('ownedBy' => $this->getUser()->getId()), array("date" => "desc"));


        return $this->render('LGMyAEBundle:Devis:index.html.twig', array(
          'devis'           => $devis,
        ));
    }

    public function addAction(Request $request, $clientSlug)
    {
        $devis = new Devis();
        $devisForm = $this->createForm(DevisType::class, $devis);


        if ($request->isMethod('POST') && $devisForm->handleRequest($request)->isValid()) {
             $formater = $this->container->get('lg_my_ae.formater');

            $name = "Devis-".str_replace('\'', '',str_replace(' ', '',$formater->removeAccents($devis->getClient()->getName()))."-".$devis->getDate()->format('ymd'));
            $total = 0;
            $subtotals = array();
            foreach($devis->getLignes() as $ligne) {
                if($ligne->getType() == "default"){
                    $totalLigne = $ligne->getQuantity()*$ligne->getPu()*(100-$ligne->getRemise())/100;
                    $total += $totalLigne;
                    if($ligne->getSubtotals() != 0){
                        if(!isset($subtotals[$ligne->getSubtotals()])){
                            $subtotals[$ligne->getSubtotals()] = 0;
                        }
                        $subtotals[$ligne->getSubtotals()] += $totalLigne;
                    }
                }
            }
            foreach($devis->getLignes() as $ligne) {
                if($ligne->getType() == "subtotal"){
                    $ligne->setPu($subtotals[$ligne->getSubtotals()]);
                    $ligne->setQuantity(1);
                    $ligne->setRemise(100 - $ligne->getRemise());
                }
            }
            $devis->setName($name);
            $devis->setTotalTTC($total);

            $em = $this->getDoctrine()->getManager();
            $em->persist($devis);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Le devis '.$devis->getName().' a bien été crée.');
            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirectToRoute('devis_view', array('id' => $devis->getId()));
        }


        return $this->render('LGMyAEBundle:Devis:add.html.twig', array(
          'addEdit'    => "add",
          'devisForm' => $devisForm->createView(),
        ));
    }

    public function removeAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('LGMyAEBundle:Devis');

        $devis = $repository->find($id);

        if (null === $devis) {
          throw new NotFoundHttpException("Le devis d'id ".$id." n'existe pas.");
        }
        $em->remove($devis);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', 'Le devis '.$devis->getName().' a bien été supprimé.');

        return $this->redirectToRoute('devis_list');
    }

    public function editAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LGMyAEBundle:Devis');
        $devis = $repository->find($id);
        if (null === $devis) {
          throw new NotFoundHttpException("Le devis d'id ".$id." n'existe pas.");
        }
        $devisEditForm = $this->createForm(DevisEditType::class, $devis);

        if ($request->isMethod('POST') && $devisEditForm->handleRequest($request)->isValid()) {
             $formater = $this->container->get('lg_my_ae.formater');

            $name = "Devis-".str_replace('\'', '',str_replace(' ', '',$formater->removeAccents($devis->getClient()->getName()))."-".$devis->getDate()->format('ymd'));
            $total = 0;
            $subtotals = array();
            foreach($devis->getLignes() as $ligne) {
                $ligne->setDevis($devis);
                if($ligne->getType() == "default"){
                    $totalLigne = $ligne->getQuantity()*$ligne->getPu()*(100-$ligne->getRemise())/100;
                    $total += $totalLigne;
                    if($ligne->getSubtotals() != 0){
                        if(!isset($subtotals[$ligne->getSubtotals()])){
                            $subtotals[$ligne->getSubtotals()] = 0;
                        }
                        $subtotals[$ligne->getSubtotals()] += $totalLigne;
                    }
                }
            }
            foreach($devis->getLignes() as $ligne) {
                if($ligne->getType() == "subtotal"){
                    $ligne->setPu($subtotals[$ligne->getSubtotals()]);
                    $ligne->setQuantity(1);
                    $ligne->setRemise(100 - $ligne->getRemise());
                }
            }
            $devis->setName($name);
            $devis->setTotalTTC($total);

            $em = $this->getDoctrine()->getManager();
            $em->persist($devis);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Le devis '.$devis->getName().' a bien été modifié.');
            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirectToRoute('devis_view', array('id' => $devis->getId()));
        }


        return $this->render('LGMyAEBundle:Devis:add.html.twig', array(
          'addEdit'    => "edit",
          'devisForm' => $devisEditForm->createView(),
        ));
    }

    public function viewAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LGMyAEBundle:Devis');

        $devis = $repository->find($id);

        if (null === $devis) {
          throw new NotFoundHttpException("Le devis d'id ".$id." n'existe pas.");
        }

        return $this->render('LGMyAEBundle:Devis:view.html.twig', array(
          'devis' => $devis
        ));

    }

    public function downloadAction(Request $request, $id, $mode, $slug, $inline)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LGMyAEBundle:Devis');


        if($mode == "id"){
            $devis = $repository->find($id);
        }else{
            $devis = $repository->findOneBy(array('slug' => $slug));
        }


        if (null === $devis) {
          throw new NotFoundHttpException("Le devis d'id ".$id." n'existe pas.");
        }

        $snappy = $this->get('knp_snappy.pdf');

        $html = $this->renderView('LGMyAEBundle:Devis:template.html.twig', array(
            'base_dir'  => $this->get('kernel')->getRootDir() . '/../web' . $request->getBasePath(),
            'devis'     => $devis
        ));

        $filename = $devis->getName();

        $contentDisposition = ($inline == true)?'inline; filename="'.$filename.'.pdf"':'attachement; filename="'.$filename.'.pdf"';

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => $contentDisposition
            )
        );
    }

}