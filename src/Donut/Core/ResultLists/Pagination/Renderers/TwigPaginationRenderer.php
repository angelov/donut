<?php

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
