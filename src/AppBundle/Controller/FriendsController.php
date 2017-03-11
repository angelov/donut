<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FriendsController extends Controller
{
    /**
     * @Route("/friends", name="app.friends.index", methods={"GET", "HEAD"})
     */
    public function index()
    {
        return $this->render('friends/index.html.twig');
    }
}
