<?php

namespace SocNet\Core\ResultLists\Pagination\UrlGenerators;

interface PaginationUrlGeneratorInterface
{
    public function generateNextPageUrl(string $pageAttribute = 'page') : string;

    public function generatePreviousPageUrl(string $pageAttribute = 'page') : string;
}
