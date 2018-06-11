<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2018, Dejan Angelov <angelovdejan92@gmail.com>
 *
 * This file is part of Donut Social Network.
 *
 * Donut Social Network is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Donut Social Network is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Donut Social Network.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package Donut Social Network
 * @copyright Copyright (C) 2016-2018, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace AppBundle\Controller;

use Angelov\Donut\Core\ResultLists\Sorting\OrderDirection;
use Angelov\Donut\Movies\MoviesList\MoviesListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Angelov\Donut\Core\ResultLists\Sorting\OrderField;
use Angelov\Donut\Movies\Genre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MoviesController extends AbstractController
{
    /**
     * @Route("/movies", name="app.movies.index")
     */
    public function indexAction(Request $request, MoviesListInterface $moviesList) : Response
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

        $moviesList->filterByGenres($genresFilter);
        $moviesList->filterByPeriod($periodFilter[0], $periodFilter[1]);
        $moviesList->setItemsPerPage($perPage);
        $moviesList->orderBy([
                new OrderField('movie.year', OrderDirection::DESC),
                new OrderField('movie.title', OrderDirection::ASC)
            ]);
        $moviesList->setOffset($offset);

        $genres = $this->getDoctrine()->getManager()->getRepository(Genre::class)->findAll();

        return $this->render('movies/index.html.twig', [
            'movies_list' => $moviesList,
            'genres' => $genres,
            'selected_genres' => $selectedGenres,
            'selected_period' => $selectedPeriod
        ]);
    }
}
