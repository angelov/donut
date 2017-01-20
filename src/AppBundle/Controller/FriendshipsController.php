<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FriendshipRequest;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FriendshipsController extends Controller
{
    /**
     * @Route("/friendships/send/{id}", name="friendships.requests.store", methods={"GET"})
     */
    public function sendFriendshipRequestAction(User $user)
    {
        $friendshipRequest = new FriendshipRequest();
        $currentUser = $this->getUser();

        $friendshipRequest->setFromUser($currentUser);
        $friendshipRequest->setToUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($friendshipRequest);
        $em->flush();

        $this->addFlash('success', 'Friendship request successfully sent!');

        return $this->redirectToRoute('app.users.index');
    }
}
