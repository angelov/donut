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

    /**
     * @Route("/friendships/cancel/{id}", name="friendships.requests.cancel", methods={"GET"})
     */
    public function cancelFriendshipRequestAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(FriendshipRequest::class);

        $friendshipRequest = $repository->findOneBy([
            'fromUser' => $this->getUser(),
            'toUser' => $user
        ]);

        if (!$friendshipRequest) {
            $this->addFlash('error', 'Something went wrong!');

            return $this->redirectToRoute('app.users.index');
        }

        $em->remove($friendshipRequest);
        $em->flush();

        $this->addFlash('success', 'Friendship request successfully cancelled!');

        return $this->redirectToRoute('app.users.index');
    }

    /**
     * @Route("/friendships/decline/{id}", name="friendships.requests.decline", methods={"GET"})
     */
    public function declineFriendshipRequestAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(FriendshipRequest::class);

        $friendshipRequest = $repository->findOneBy([
            'fromUser' => $user,
            'toUser' => $this->getUser()
        ]);

        if (!$friendshipRequest) {
            $this->addFlash('error', 'Something went wrong!');

            return $this->redirectToRoute('app.users.index');
        }

        $em->remove($friendshipRequest);
        $em->flush();

        $this->addFlash('success', 'Friendship request successfully declined!');

        return $this->redirectToRoute('app.users.index');
    }

    /**
     * @Route("/friendships/{id}", name="friendships.requests.accept")
     */
    public function acceptFriendshipRequestAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(FriendshipRequest::class);

        $friendshipRequest = $repository->findOneBy([
            'fromUser' => $user,
            'toUser' => $this->getUser()
        ]);

        if (!$friendshipRequest) {
            $this->addFlash('error', 'Something went wrong!');

            return $this->redirectToRoute('app.users.index');
        }

        $em->remove($friendshipRequest);
        $em->flush();

        // @todo: store the new friendship

        $this->addFlash('success', 'Friendship request successfully accepted!');

        return $this->redirectToRoute('app.users.index');
    }
}
