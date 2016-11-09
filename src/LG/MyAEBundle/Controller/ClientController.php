<?php

namespace LG\MyAEBundle\Controller;

use LG\MyAEBundle\Entity\Client;
use LG\MyAEBundle\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use LG\MyAEBundle\Form\ClientType;
use LG\MyAEBundle\Form\ClientEditType;

class ClientController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $clientRepository = $em->getRepository('LGMyAEBundle:Client');
        $clients = $clientRepository->findBy(array('ownedBy' => $this->getUser()->getUsername()), array("dateActif" => "desc"));


        return $this->render('LGMyAEBundle:Client:index.html.twig', array(
          'clients'           => $clients,
        ));
    }

    public function addAction(Request $request)
    {
        $client = new Client();
        $clientForm = $this->createForm(ClientType::class, $client);


        if ($request->isMethod('POST') && $clientForm->handleRequest($request)->isValid()) {
            foreach($client->getAddresses() as $address) {
                $address->setClient($client);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Le client '.$client->getName().' a bien été ajouté.');
            // On redirige vers la page de visualisation de l'annonce nouvellement créée
            return $this->redirectToRoute('client_view', array('id' => $client->getId()));
        }


        return $this->render('LGMyAEBundle:Client:add.html.twig', array(
          'addEdit'    => "add",
          'clientForm' => $clientForm->createView(),
        ));
    }

    public function removeAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('LGMyAEBundle:Client');

        $client = $repository->find($id);

        if (null === $client) {
          throw new NotFoundHttpException("Le client d'id ".$id." n'existe pas.");
        }
        $em->remove($client);

        $em->flush();

        $request->getSession()->getFlashBag()->add('info', 'Le client '.$client->getName().' a bien été supprimé.');

        return $this->redirectToRoute('client_list');
    }

    public function editAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LGMyAEBundle:Client');
        $client = $repository->find($id);
        if (null === $client) {
          throw new NotFoundHttpException("Le client d'id ".$id." n'existe pas.");
        }
        $clientEditForm = $this->createForm(ClientEditType::class, $client);

        if ($request->isMethod('POST') && $clientEditForm->handleRequest($request)->isValid()) {
            foreach($client->getAddresses() as $address) {
                $address->setClient($client);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'Le client '.$client->getName().' a bien été modifié.');
            return $this->redirectToRoute('client_view', array('id' => $client->getId()));
        }


        return $this->render('LGMyAEBundle:Client:add.html.twig', array(
          'addEdit'    => "edit",
          'clientForm' => $clientEditForm->createView(),
        ));
    }

    public function viewAction($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('LGMyAEBundle:Client');

        $client = $repository->find($id);

        if (null === $client) {
          throw new NotFoundHttpException("Le client d'id ".$id." n'existe pas.");
        }

        // Le render ne change pas, on passait avant un tableau, maintenant un objet
        return $this->render('LGMyAEBundle:Client:view.html.twig', array(
          'client' => $client
        ));
    }

}