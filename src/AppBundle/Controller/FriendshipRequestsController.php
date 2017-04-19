<?php

namespace AppBundle\Controller;

use SocNet\Friendships\Friendship;
use SocNet\Friendships\FriendshipRequests\Commands\AcceptFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\Commands\CancelFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\Commands\DeclineFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\Commands\SendFriendshipRequestCommand;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use SocNet\Users\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FriendshipRequestsController extends Controller
{
    /**
     * @Route("/friendships/send/{id}", name="friendships.requests.store", methods={"GET"})
     */
    public function sendFriendshipRequestAction(User $user) : Response
    {
        $currentUser = $this->getUser();

        $this->get('app.core.command_bus.default')->handle(new SendFriendshipRequestCommand($currentUser, $user));

        $this->addFlash('success', 'Friendship request successfully sent!');

        return $this->redirectToRoute('app.friends.index');
    }

    /**
     * @Route("/friendships/cancel/{id}", name="friendships.requests.cancel", methods={"GET"})
     */
    public function cancelFriendshipRequestAction(User $user) : Response
    {
        // @todo use a voter to check if the user can cancel the request

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(FriendshipRequest::class);

        /** @var FriendshipRequest $friendshipRequest */
        $friendshipRequest = $repository->findOneBy([
            'fromUser' => $this->getUser(),
            'toUser' => $user
        ]);

        if (!$friendshipRequest) {
            $this->addFlash('error', 'Something went wrong!');

            return $this->redirectToRoute('app.friends.index');
        }

        $this->get('app.core.command_bus.default')->handle(new CancelFriendshipRequestCommand($friendshipRequest));

        $this->addFlash('success', 'Friendship request successfully cancelled!');

        return $this->redirectToRoute('app.friends.index');
    }

    /**
     * @Route("/friendships/decline/{id}", name="friendships.requests.decline", methods={"GET"})
     */
    public function declineFriendshipRequestAction(User $user) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(FriendshipRequest::class);

        $friendshipRequest = $repository->findOneBy([
            'fromUser' => $user,
            'toUser' => $this->getUser()
        ]);

        if (!$friendshipRequest) {
            $this->addFlash('error', 'Something went wrong!');

            return $this->redirectToRoute('app.friends.index');
        }

        $this->get('app.core.command_bus.default')->handle(new DeclineFriendshipRequestCommand($friendshipRequest));

        $this->addFlash('success', 'Friendship request successfully declined!');

        return $this->redirectToRoute('app.friends.index');
    }

    /**
     * @Route("/friendships/{id}", name="friendships.requests.accept")
     */
    public function acceptFriendshipRequestAction(User $user) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(FriendshipRequest::class);

        $friendshipRequest = $repository->findOneBy([
            'fromUser' => $user,
            'toUser' => $this->getUser()
        ]);

        if (!$friendshipRequest) {
            $this->addFlash('error', 'Something went wrong!');

            return $this->redirectToRoute('app.friends.index');
        }

        $this->get('app.core.command_bus.default')->handle(new AcceptFriendshipRequestCommand($friendshipRequest));

        $this->addFlash('success', 'Friendship request successfully accepted!');

        return $this->redirectToRoute('app.friends.index');
    }
}
