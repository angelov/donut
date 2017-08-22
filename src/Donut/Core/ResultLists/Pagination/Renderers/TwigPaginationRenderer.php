<?php

/**
 * Donut Social Network - Yet another experimental social network.
 * Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
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
 * @copyright Copyright (C) 2016-2017, Dejan Angelov <angelovdejan92@gmail.com>
 * @license https://github.com/angelov/donut/blob/master/LICENSE
 * @author Dejan Angelov <angelovdejan92@gmail.com>
 */

namespace Angelov\Donut\Core\ResultLists\Pagination\Renderers;

use Angelov\Donut\Core\ResultLists\Pagination\CurrentPageResolvers\CurrentPageResolverInterface;
use Angelov\Donut\Core\ResultLists\Pagination\PageExistenceChecker\PageExistenceCheckerInterface;
use Angelov\Donut\Core\ResultLists\Pagination\PaginatableResultsInterface;
use Angelov\Donut\Core\ResultLists\Pagination\UrlGenerators\PaginationUrlGeneratorInterface;
use Twig_Environment;

class TwigPaginationRenderer implements PaginationRendererInterface
{
    private $twig;
    private $urlGenerator;
    private $currentPageResolver;
    private $pageExistenceChecker;

    public function __construct(
        Twig_Environment $twig,
        PaginationUrlGeneratorInterface $urlGenerator,
        CurrentPageResolverInterface $currentPageResolver,
        PageExistenceCheckerInterface $pageExistenceChecker
    ) {
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
        $this->currentPageResolver = $currentPageResolver;
        $this->pageExistenceChecker = $pageExistenceChecker;
    }

    public function render(PaginatableResultsInterface $list, string $pageAttribute = 'page'): string
    {
        $currentPage = $this->currentPageResolver->resolve($pageAttribute);

        $hasNextPage = $this->pageExistenceChecker->hasNextPage($list, $currentPage);
        $hasPreviousPage = $this->pageExistenceChecker->hasPreviousPage($list, $currentPage);

        $nextUrl = $this->urlGenerator->generateNextPageUrl($pageAttribute);
        $prevUrl = $this->urlGenerator->generatePreviousPageUrl($pageAttribute);

        // @todo handle exception if file doesn't exist
        return $this->twig->render('core/result-lists/pagination/pagination.html.twig', [
            'current_page' => $currentPage,
            'has_next_page' => $hasNextPage,
            'next_page_url' => $nextUrl,
            'has_previous_page' => $hasPreviousPage,
            'previous_page_url' => $prevUrl
        ]);
    }
}
