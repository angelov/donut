<?php

namespace AppBundle\Controller;

use SocNet\Communities\Community;
use SocNet\Communities\Form\CommunityType;
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
        $repository = $this->getDoctrine()->getManager()->getRepository(Community::class);

        $communities = $repository->findAll();

        return $this->render('communities/index.html.twig', [
            'communities' => $communities
        ]);
    }

    /**
     * @Route("/communities/create", name="app.communities.create")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CommunityType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Community $community */
            $community = $form->getData();
            $community->setAuthor($this->getUser());

            $repository = $this->get('app.communities.repositories.default');
            $repository->store($community);

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
    public function showAction(Community $community)
    {
        return $this->render('communities/show.html.twig', [
            'community' => $community
        ]);
    }

    /**
     * @Route("/communities/{id}/join", name="app.communities.join", methods={"POST"})
     */
    public function joinAction(Community $community)
    {
        $user = $this->getUser();

        $community->addMember($user);

        $em = $this->getDoctrine()->getManager();

        $em->persist($community);
        $em->flush();

        $this->addFlash('success', 'Successfully joined the community');

        return $this->redirectToRoute('app.communities.index');
    }

    /**
     * @Route("/communities/{id}/leave", name="app.communities.leave", methods={"POST"})
     */
    public function leaveAction(Community $community)
    {
        $user = $this->getUser();

        if ($community->hasMember($user)) {
            $community->removeMember($user);

            $em = $this->getDoctrine()->getManager();

            $em->persist($community);
            $em->flush();

            $this->addFlash('success', 'Successfully left the community');
        }

        return $this->redirectToRoute('app.communities.index');
    }
}
