<?php

namespace AppBundle\MoviesList;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

// @todo refactor
class PaginationRenderer
{
    private $requestStack;
    private $router;

    public function __construct(RequestStack $requestStack, RouterInterface $router)
    {
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    public function render(/* MoviesListInterface */ $list, string $pageAttribute = 'page') : string
    {
        $request = $this->requestStack->getCurrentRequest();

        $currentPage = (int) $request->query->get($pageAttribute, 1);
        $nextPage = $currentPage + 1;

        $hasNextPage = ($list->getOffset() + $list->getItemsPerPage()) <= ($list->countTotal() - 1);
        $hasPreviousPage = $currentPage > 1;

        $request->query->set('page', $nextPage);
        $attributes = $request->query->all();

        $route = $request->attributes->get('_route');

        $nextUrl = $this->router->generate($route, $attributes);

        $request->query->set('page', $currentPage-1);
        $attributes = $request->query->all();

        $prevUrl = $this->router->generate($route, $attributes);

        return $this->generateHtml($hasNextPage, $hasPreviousPage, $nextUrl, $prevUrl);
    }

    private function generateHtml($hasNext, $hasPrevious, $nextUrl, $prevUrl)
    {
        $html = '';

        if ($hasNext || $hasPrevious) {
            $html .= "<nav aria-label=\"...\"><ul class=\"pagination\">";
        }

        if ($hasPrevious) {
            $html .= "<li class=\"\"><a href=\"". $prevUrl ."\">&larr; Previous page</a></li>";
        }

        if ($hasNext) {
            $html .= "<li class=\"\"><a href=\"". $nextUrl ."\">Next page &rarr;</a></li>";
        }

        if ($hasNext || $hasPrevious) {
            $html .= "</ul></nav>";
        }

        return $html;
    }
}
