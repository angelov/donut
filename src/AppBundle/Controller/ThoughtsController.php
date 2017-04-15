<?php

namespace AppBundle\Controller;

use SocNet\Thoughts\Commands\DeleteThoughtCommand;
use SocNet\Thoughts\Commands\StoreThoughtCommand;
use SocNet\Thoughts\Thought;
use SocNet\Thoughts\Form\ThoughtType;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ThoughtsController extends Controller
{
    /**
     * @Route("/thoughts", name="app.thoughts.index", methods={"GET", "HEAD", "POST"})
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var StoreThoughtCommand $thought */
            $command = $form->getData();

            $this->get('app.core.command_bus.default')->handle($command);

            $user = $this->getUser();

            $counter = $this->get('app.thoughts.thoughts_counter.default');
            $counter->increase($user);

            $this->addFlash('success', 'Thought shared!');

            return $this->redirectToRoute('app.thoughts.index');
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'pager' => $pager
        ]);
    }

    /**
     * @Route("/thoughts/{id}", name="app.thoughts.delete", methods={"POST"})
     * @todo change to use DELETE method
     */
    public function delete(Thought $thought)
    {
        if (!$this->isGranted('DELETE_THOUGHT', $thought)) {
            return $this->redirectToRoute('app.thoughts.index');
        }

        $this->get('app.core.command_bus.default')->handle(new DeleteThoughtCommand($thought));

        $this->addFlash('success', 'Thought deleted!');

        return $this->redirectToRoute('app.thoughts.index');
    }
}
