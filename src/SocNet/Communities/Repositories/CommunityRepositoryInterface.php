<?php

namespace SocNet\Communities\Repositories;

use SocNet\Communities\Community;

interface CommunityRepositoryInterface
{
    public function store(Community $community) : void;

    public function find(string $id) : Community;
}
