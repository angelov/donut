<?php

namespace AppBundle\Controller;

use AppBundle\MoviesList\OrderDirection;
use AppBundle\MoviesList\OrderField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SocNet\Movies\Genre;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MoviesController extends Controller
{
    /**
     * @Route("/movies", name="app.movies.index")
     */
    public function indexAction(Request $request)
    {
        $selectedGenres = [];
        foreach ($request->query->all() as $item => $value) {
            $p = explode('genre_', $item);
            if (count($p) > 1) {
                $selectedGenres[] = $p[1];
            }
        }
        $genresFilter = $this->getDoctrine()->getManager()->getRepository(Genre::class)->findBy(['id' => $selectedGenres]);

        $selectedPeriod = $request->get('period', '1000-3000');
        $periodFilter = explode('-', $selectedPeriod);

        $page = $request->query->get('page', 1);
        $perPage = 12;
        $offset = ($page-1)*$perPage;

        $moviesList = $this
            ->get('app.movies.movies_list')
            ->filterByGenres($genresFilter)
            ->filterByPeriod($periodFilter[0], $periodFilter[1])
            ->setItemsPerPage($perPage)
//            ->orderBy(['movie.year' => 'DESC', 'movie.title' => 'ASC'])
            ->orderBy([
                new OrderField('movie.year', OrderDirection::DESC),
                new OrderField('movie.title', OrderDirection::ASC)
            ])
            ->setOffset($offset);

        $genres = $this->getDoctrine()->getManager()->getRepository(Genre::class)->findAll();

        return $this->render('movies/index.html.twig', [
            'movies_list' => $moviesList,
            'genres' => $genres,
            'selected_genres' => $selectedGenres,
            'selected_period' => $selectedPeriod
        ]);
    }
}
