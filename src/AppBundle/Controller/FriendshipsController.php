<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Friendship;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FriendshipsController extends Controller
{
    /**
     * @Route("/friendships/remove/{id}", name="app.friendships.remove", methods={"GET"})
     * @todo fix to use delete requests
     */
    public function delete(User $user)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(Friendship::class);

        if (! $user->isFriendWith($this->getUser())) {
            $this->addFlash('error', 'Can\'t broke a nonexisting friendship.');

            return $this->redirectToRoute('app.friends.index');
        }

        $friendship = $repository->findOneBy([
            'user' => $user,
            'friend' => $this->getUser()
        ]);

        $em->remove($friendship);

        $friendship = $repository->findOneBy([
            'friend' => $user,
            'user' => $this->getUser()
        ]);

        $em->remove($friendship);

        $em->flush();

        $this->addFlash('success', 'Sorry to see broken friendships.');

        return $this->redirectToRoute('app.friends.index');
    }
}
