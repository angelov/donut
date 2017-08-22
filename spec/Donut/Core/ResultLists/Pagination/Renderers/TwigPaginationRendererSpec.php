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

namespace spec\Angelov\Donut\Core\ResultLists\Pagination\Renderers;

use Prophecy\Argument;
use Angelov\Donut\Core\ResultLists\Pagination\CurrentPageResolvers\CurrentPageResolverInterface;
use Angelov\Donut\Core\ResultLists\Pagination\PageExistenceChecker\PageExistenceCheckerInterface;
use Angelov\Donut\Core\ResultLists\Pagination\PaginatableResultsInterface;
use Angelov\Donut\Core\ResultLists\Pagination\Renderers\PaginationRendererInterface;
use Angelov\Donut\Core\ResultLists\Pagination\Renderers\TwigPaginationRenderer;
use PhpSpec\ObjectBehavior;
use Angelov\Donut\Core\ResultLists\Pagination\UrlGenerators\PaginationUrlGeneratorInterface;
use Twig_Environment;

class TwigPaginationRendererSpec extends ObjectBehavior
{
    function let(
        Twig_Environment $twig,
        PaginationUrlGeneratorInterface $urlGenerator,
        CurrentPageResolverInterface $currentPageResolver,
        PageExistenceCheckerInterface $pageExistenceChecker,
        PaginatableResultsInterface $list
    ) {
        $this->beConstructedWith($twig, $urlGenerator, $currentPageResolver, $pageExistenceChecker);

        $urlGenerator->generateNextPageUrl(Argument::any())->willReturn('next-url');
        $urlGenerator->generatePreviousPageUrl(Argument::any())->willReturn('prev-url');
        $currentPageResolver->resolve(Argument::any())->willReturn(3);
        $pageExistenceChecker->hasNextPage($list, 3)->willReturn(true);
        $pageExistenceChecker->hasPreviousPage($list, 3)->willReturn(true);

    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TwigPaginationRenderer::class);
    }

    function it_is_pagination_renderer()
    {
        $this->shouldImplement(PaginationRendererInterface::class);
    }

    function it_uses_twig_to_render_pagination(PaginatableResultsInterface $list, Twig_Environment $twig)
    {
        $twig->render('core/result-lists/pagination/pagination.html.twig', [
            'current_page' => 3,
            'has_next_page' => true,
            'next_page_url' => 'next-url',
            'has_previous_page' => true,
            'previous_page_url' => 'prev-url'
        ])
            ->shouldBeCalled()
            ->willReturn('rendered-html');

        $this->render($list)->shouldReturn('rendered-html');
    }
}
