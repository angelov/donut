<?php

namespace SocNet\Communities\Repositories;

use SocNet\Communities\Community;

interface CommunitiesRepositoryInterface
{
    public function store(Community $community) : void;

    public function find(string $id) : Community;

    /**
     * @return Community[]
     */
    public function all() : array;
}
