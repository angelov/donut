<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Thought;
use AppBundle\Form\ThoughtType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ThoughtsController extends Controller
{
    /**
     * @Route("/thoughts", name="app.thoughts.index", methods={"GET", "HEAD"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Thought::class);

        $form = $this->createForm(ThoughtType::class);
        $thoughts = $repository->findAll();

        return $this->render('default/index.html.twig', [
            'thoughts' => $thoughts,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/thoughts", name="app.thoughts.store", methods={"POST"})
     */
    public function storeAction(Request $request)
    {
        $form = $this->createForm(ThoughtType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            /** @var Thought $thought */
            $thought = $form->getData();

            $em->persist($thought);
            $em->flush();

            $this->addFlash('success', 'Thought shared!');

        }

        return $this->redirectToRoute('app.thoughts.index');
    }
}
