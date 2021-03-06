<?php

namespace LG\MyAEBundle\Controller;

use LG\MyAEBundle\Entity\Client;
use LG\MyAEBundle\Entity\Address;
use LG\MyAEBundle\Entity\Devis;
use LG\MyAEBundle\Entity\Ligne;
use LG\MyAEBundle\Entity\Facture;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use LG\MyAEBundle\Form\FactureType;
use LG\MyAEBundle\Form\FactureEditType;

class FactureController extends Controller
{
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $factureRepository = $em->getRepository('LGMyAEBundle:Facture');
        $factures = $factureRepository->findBy(array('ownedBy' => $this->getUser()->getId()), array("prestation_date" => "desc"));

        return $this->render('LGMyAEBundle:Facture:index.html.twig', array(
          'factures'           => $factures,
        ));
    }

    public function addAction(Request $request, $clientSlug, $mode, $id)
    {
        $facture = new Facture(); 
        $factureForm = $this->createForm(FactureType::class, $facture, array('user' => $this->getUser()->getUsername()));

        // Check if client have a facturation address.

        if ($request->isMethod('POST') && $factureForm->handleRequest($request)->isValid()) {
            $formater = $this->container->get('lg_my_ae.formater');
            $total = 0;
            $subtotals = array();
            foreach($facture->getLignes() as $ligne) {
                if($ligne->getType() == "default"){
                    $totalLigne = $ligne->getQuantity()*$ligne->getPu();
                    $total += $totalLigne;
                    if($ligne->getSubtotals() != 0){
                        if(!isset($subtotals[$ligne->getSubtotals()])){
                            $subtotals[$ligne->getSubtotals()] = 0;
                        }
                        $subtotals[$ligne->getSubtotals()] += $totalLigne;
                    }
                }
            }
            foreach($facture->getLignes() as $ligne) {
                if($ligne->getType() == "subtotal"){
                    $ligne->setPu($subtotals[$ligne->getSubtotals()]);
                    $ligne->setQuantity(1);
                }
            }
            $facture->setTotalTTC($total);


            $em = $this->getDoctrine()->getManager();
            $last = $em->getRepository("LGMyAEBundle:Facture")->findOneBy(
                    array('ownedBy' => $this->getUser()),
                    array('facture_number' => 'DESC')
            );
            if($last){
                $number = $last->getFactureNumber() + 1;
            }else{
                $number = 1;
            }

            $facture->setFactureNumber($number);


            $em->persist($facture);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'La facture numéro '.$facture->getFactureNumber().' a bien été crée.');

            return $this->redirectToRoute('facture_view', array('id' => $facture->getId()));
        }

        if($mode == "accompte" && $id){
            $em = $this->getDoctrine()->getManager();
            $devis = $em->getRepository("LGMyAEBundle:Devis")->findOneBy(
                    array('id' => $id)
            );
            $facture->setType("Accompte");
            $ligne = new Ligne();
            $ligne->setType("default");
            $ligne->setTitle("Accompte associé au devis ".$devis->getName());
            $ligne->setQuantity(1);
            $ligne->setPu(ceil($devis->getTotalTTC() * 0.3));
            $facture->addLigne($ligne);
        }elseif($mode == "facture" && $id){
            $em = $this->getDoctrine()->getManager();
            $devis = $em->getRepository("LGMyAEBundle:Devis")->findOneBy(
                    array('id' => $id)
            );

            foreach($devis->getLignes() as $ligne){
                $newLine = new Ligne();
                $newLine->setType($ligne->getType());
                $newLine->setTitle($ligne->getTitle());
                $newLine->setQuantity($ligne->getQuantity());
                $newLine->setPu(ceil($ligne->getPu() * (100-$ligne->getRemise()) / 100));
                $newLine->setSubtotals($ligne->getSubtotals());
                $newLine->setSubtotals($ligne->getSubtotals());
                $newLine->setOrdre($ligne->getOrdre());
                $facture->addLigne($newLine);
            }
            
            $facture->setType("Facture");
        }

        $factureForm = $this->createForm(FactureType::class, $facture, array('user' => $this->getUser()->getUsername()));
        return $this->render('LGMyAEBundle:Facture:add.html.twig', array(
          'addEdit'    => "add",
          'factureForm' => $factureForm->createView(),
        ));
    }


    public function removeAction(Request $request, $id)
    {
        throw new NotFoundHttpException("Vous ne pouvez pas supprimer une facture.");
        return false;        

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('LGMyAEBundle:Facture');

        $facture = $repository->find($id);

        if (null === $facture) {
          throw new NotFoundHttpException("La facture d'id ".$id." n'existe pas.");
        }
        $em->remove($facture);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', 'La facture numéro '.$facture->getFactureNumber().' a bien été supprimé.');

        return $this->redirectToRoute('facture_list');
    }


    public function editAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LGMyAEBundle:Facture');
        $facture = $repository->find($id);
        if (null === $facture) {
          throw new NotFoundHttpException("La facture d'id ".$id." n'existe pas.");
        }
        $factureEditForm = $this->createForm(FactureEditType::class, $facture, array('user' => $this->getUser()->getUsername()));

        if ($request->isMethod('POST') && $factureEditForm->handleRequest($request)->isValid()) {
             $formater = $this->container->get('lg_my_ae.formater');

            $total = 0;
            $subtotals = array();
            foreach($facture->getLignes() as $ligne) {
                if($ligne->getType() == "default"){
                    $totalLigne = $ligne->getQuantity()*$ligne->getPu();
                    $total += $totalLigne;
                    if($ligne->getSubtotals() != 0){
                        if(!isset($subtotals[$ligne->getSubtotals()])){
                            $subtotals[$ligne->getSubtotals()] = 0;
                        }
                        $subtotals[$ligne->getSubtotals()] += $totalLigne;
                    }
                }
            }
            foreach($facture->getLignes() as $ligne) {
                if($ligne->getType() == "subtotal"){
                    $ligne->setPu($subtotals[$ligne->getSubtotals()]);
                    $ligne->setQuantity(1);
                }
            }
            $facture->setTotalTTC($total);

            $em = $this->getDoctrine()->getManager();
            $em->persist($facture);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'La facture numéro '.$facture->getFactureNumber().' a bien été modifié.');
            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirectToRoute('facture_view', array('id' => $facture->getId()));
        }


        return $this->render('LGMyAEBundle:Facture:add.html.twig', array(
          'addEdit'    => "edit",
          'factureForm' => $factureEditForm->createView(),
        ));
    }

    public function viewAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LGMyAEBundle:Facture');

        $facture = $repository->find($id);

        if (null === $facture) {
          throw new NotFoundHttpException("La facture d'id ".$id." n'existe pas.");
        }

        return $this->render('LGMyAEBundle:Facture:view.html.twig', array(
          'facture' => $facture
        ));

    }

    public function downloadAction(Request $request, $id, $mode, $slug, $inline)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LGMyAEBundle:Facture');


        if($mode == "id"){
            $facture = $repository->find($id);
        }else{
            $facture = $repository->findOneBy(array('slug' => $slug));
        }


        if (null === $facture) {
          throw new NotFoundHttpException("La facture d'id ".$id." n'existe pas.");
        }

        $snappy = $this->get('knp_snappy.pdf');

        $html = $this->renderView('LGMyAEBundle:Facture:template.html.twig', array(
            'base_dir'  => $this->get('kernel')->getRootDir() . '/../web' . $request->getBasePath(),
            'facture'     => $facture
        ));

        $filename = "Facture-".$facture->getFactureNumber()."-".$facture->getClient()->getSlug().".pdf";

        $contentDisposition = ($inline == true)?'inline; filename="'.$filename.'"':'attachement; filename="'.$filename.'"';

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
