<?php

namespace SocNet\Core\ResultLists\Pagination;

use SocNet\Core\ResultLists\Pagination\Renderers\PaginationRendererInterface;
use Twig_Extension;

class PaginationRendererTwigExtension extends Twig_Extension
{
    private $renderer;

    public function __construct(PaginationRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getFunctions() : array
    {
        return [
            new \Twig_SimpleFunction('render_pagination', [$this, 'renderPagination'])
        ];
    }

    public function renderPagination(PaginatableResultsInterface $list, string $pageAttribute = 'page') : string
    {
        return (string) $this->renderer->render($list, $pageAttribute);
    }
}
