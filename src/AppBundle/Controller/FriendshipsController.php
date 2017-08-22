<?php

namespace AppBundle\Controller;

use Angelov\Donut\Friendships\Commands\DeleteFriendshipCommand;
use Angelov\Donut\Friendships\Friendship;
use Angelov\Donut\Users\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FriendshipsController extends Controller
{
    /**
     * @Route("/friendships/remove/{id}", name="app.friendships.remove", methods={"GET"})
     * @todo fix to use delete requests
     */
    public function delete(User $user) : Response
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(Friendship::class);

        if (! $user->isFriendWith($this->getUser())) {
            $this->addFlash('error', 'Can\'t broke a nonexisting friendship.');

            return $this->redirectToRoute('app.friends.index');
        }

        $friendship = $repository->findOneBy([
            'user' => $this->getUser(),
            'friend' => $user
        ]);

        $this->get('app.core.command_bus.default')->handle(new DeleteFriendshipCommand($friendship));

        $this->addFlash('success', 'Sorry to see broken friendships.');

        return $this->redirectToRoute('app.friends.index');
    }
}
