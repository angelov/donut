<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Thought;
use AppBundle\Form\ThoughtType;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ThoughtsController extends Controller
{
    /**
     * @Route("/thoughts", name="app.thoughts.index", methods={"GET", "HEAD"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em
            ->createQueryBuilder()
            ->select('t')
            ->from(Thought::class, 't')
            ->orderBy('t.createdAt', 'DESC');

        $adapter = new DoctrineORMAdapter($queryBuilder, false);

        $page = $request->query->get('page', 1);
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage(10);
        $pager->setCurrentPage($page);

        $form = $this->createForm(ThoughtType::class);

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'pager' => $pager
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

            $user = $this->getUser();
            $thought->setAuthor($user);

            $counter = $this->get('app.thoughts_counter.default');
            $counter->increase($user);

            $em->persist($thought);
            $em->flush();

            $this->addFlash('success', 'Thought shared!');

        }

        return $this->redirectToRoute('app.thoughts.index');
    }
}
