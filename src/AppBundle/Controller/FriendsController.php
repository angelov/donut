<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FriendsController extends Controller
{
    /**
     * @Route("/friends", name="app.friends.index", methods={"GET", "HEAD"})
     */
    public function index() : Response
    {
        return $this->render('friends/index.html.twig');
    }
}
