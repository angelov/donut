<?php

namespace AppBundle\Controller;

use AppBundle\ThoughtsList\ThoughtsListInterface;
use SocNet\Thoughts\Commands\DeleteThoughtCommand;
use SocNet\Thoughts\Commands\StoreThoughtCommand;
use SocNet\Thoughts\Thought;
use SocNet\Thoughts\Form\ThoughtType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ThoughtsController extends Controller
{
    /**
     * @Route("/thoughts", name="app.thoughts.index", methods={"GET", "HEAD", "POST"})
     */
    public function indexAction(Request $request) : Response
    {
        $form = $this->createForm(ThoughtType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var StoreThoughtCommand $thought */
            $command = $form->getData();

            $this->get('app.core.command_bus.default')->handle($command);

            $this->addFlash('success', 'Thought shared!');

            return $this->redirectToRoute('app.thoughts.index');
        }

        /** @var ThoughtsListInterface $thoughtsList */
        $thoughtsList = $this->get('app.thoughts.thoughts_list');

        $page = $request->query->get('page', 1);
        $perPage = 3;
        $offset = ($page-1)*$perPage;

        $thoughtsList
            ->filterSource(ThoughtsListInterface::FROM_FRIENDS)
            ->includeOwnThoughts()
            ->orderBy('thought.createdAt', 'DESC')
            ->setItemsPerPage($perPage)
            ->setOffset($offset);

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'thoughts_list' => $thoughtsList
        ]);
    }

    /**
     * @Route("/thoughts/{id}", name="app.thoughts.delete", methods={"POST"})
     * @todo change to use DELETE method
     */
    public function delete(Thought $thought) : Response
    {
        if (!$this->isGranted('DELETE_THOUGHT', $thought)) {
            return $this->redirectToRoute('app.thoughts.index');
        }

        $this->get('app.core.command_bus.default')->handle(new DeleteThoughtCommand($thought));

        $this->addFlash('success', 'Thought deleted!');

        return $this->redirectToRoute('app.thoughts.index');
    }
}
