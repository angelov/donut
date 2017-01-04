<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Community;
use AppBundle\Form\CommunityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommunitiesController extends Controller
{
    /**
     * @Route("/communities", name="app.communities.index", methods={"GET", "HEAD"})
     */
    public function indexAction()
    {
        return $this->render('communities/index.html.twig');
    }

    /**
     * @Route("/communities/create", name="app.communities.create")
     */
    public function createAction()
    {
        $form = $this->createForm(CommunityType::class);

        return $this->render('communities/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/communities", name="app.communities.store", methods={"POST"})
     */
    public function storeAction(Request $request)
    {
        $form = $this->createForm(CommunityType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Community $community */
            $community = $form->getData();
            $community->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($community);
            $em->flush();

            $this->addFlash('success', 'Community was successfully created!');

        }

        return $this->redirectToRoute('app.communities.index');
    }
}
