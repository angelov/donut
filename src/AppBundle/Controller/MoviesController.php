<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SocNet\Movies\Genre;
use SocNet\Movies\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MoviesController extends Controller
{
    /**
     * @Route("/movies", name="app.movies.index")
     */
    public function indexAction()
    {
        $movies = $this->getDoctrine()
            ->getManager()
            ->createQueryBuilder()
            ->select('m')
            ->from(Movie::class, 'm')
            ->setFirstResult(40)
            ->setMaxResults(20)
            ->getQuery()
            ->execute();

        $genres = $this->getDoctrine()->getManager()->getRepository(Genre::class)->findAll();

        return $this->render('movies/index.html.twig', [
            'movies' => $movies,
            'genres' => $genres
        ]);
    }
}
