<?php

namespace AppBundle\MoviesList;

use Twig_Extension;

class PaginationRendererTwigExtension extends Twig_Extension
{
    private $renderer;

    public function __construct(PaginationRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getFunctions() : array
    {
        return [
            new \Twig_SimpleFunction('render_pagination', [$this, 'renderPagination'])
        ];
    }

    public function renderPagination(/* MoviesListInterface */ $list, string $pageAttribute = 'page') : string
    {
        return (string) $this->renderer->render($list, $pageAttribute);
    }
}
