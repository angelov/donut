<?php

namespace spec\SocNet\Core\ResultLists\Pagination\Renderers;

use Prophecy\Argument;
use SocNet\Core\ResultLists\Pagination\CurrentPageResolvers\CurrentPageResolverInterface;
use SocNet\Core\ResultLists\Pagination\PageExistenceChecker\PageExistenceCheckerInterface;
use SocNet\Core\ResultLists\Pagination\PaginatableResultsInterface;
use SocNet\Core\ResultLists\Pagination\Renderers\PaginationRendererInterface;
use SocNet\Core\ResultLists\Pagination\Renderers\TwigPaginationRenderer;
use PhpSpec\ObjectBehavior;
use SocNet\Core\ResultLists\Pagination\UrlGenerators\PaginationUrlGeneratorInterface;
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
