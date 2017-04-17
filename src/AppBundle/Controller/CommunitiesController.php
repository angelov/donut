<?php

namespace AppBundle\Controller;

use SocNet\Communities\Commands\JoinCommunityCommand;
use SocNet\Communities\Commands\LeaveCommunityCommand;
use SocNet\Communities\Commands\StoreCommunityCommand;
use SocNet\Communities\Form\CommunityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommunitiesController extends Controller
{
    /**
     * @Route("/communities", name="app.communities.index", methods={"GET", "HEAD"})
     */
    public function indexAction() : Response
    {
        $repository = $this->get('app.communities.repositories.default');

        $communities = $repository->all();

        return $this->render('communities/index.html.twig', [
            'communities' => $communities
        ]);
    }

    /**
     * @Route("/communities/create", name="app.communities.create")
     */
    public function createAction(Request $request) : Response
    {
        $form = $this->createForm(CommunityType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var StoreCommunityCommand $command */
            $command = $form->getData();

            $this->get('app.core.command_bus.default')->handle($command);

            $this->addFlash('success', 'Community was successfully created!');

            return $this->redirectToRoute('app.communities.index');
        }

        return $this->render('communities/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/communities/{id}", name="app.communities.show", methods={"POST", "GET"})
     */
    public function showAction($id) : Response
    {
        $repository = $this->get('app.communities.repositories.default');
        $community = $repository->find($id);

        return $this->render('communities/show.html.twig', [
            'community' => $community
        ]);
    }

    /**
     * @Route("/communities/{id}/join", name="app.communities.join", methods={"POST"})
     */
    public function joinAction($id) : Response
    {
        $repository = $this->get('app.communities.repositories.default');
        $community = $repository->find($id);
        $user = $this->getUser();

        $this->get('app.core.command_bus.default')->handle(new JoinCommunityCommand($user, $community));

        $this->addFlash('success', 'Successfully joined the community');

        return $this->redirectToRoute('app.communities.index');
    }

    /**
     * @Route("/communities/{id}/leave", name="app.communities.leave", methods={"POST"})
     */
    public function leaveAction($id) : Response
    {
        $repository = $this->get('app.communities.repositories.default');
        $community = $repository->find($id);
        $user = $this->getUser();

        $this->get('app.core.command_bus.default')->handle(new LeaveCommunityCommand($user, $community));

        $this->addFlash('success', 'Successfully left the community');

        return $this->redirectToRoute('app.communities.index');
    }
}
