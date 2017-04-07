<?php

namespace SocNet\Communities\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use SocNet\Communities\Community;

class CommunitiesRepository implements CommunityRepositoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function store(Community $community): void
    {
        $this->em->persist($community);
        $this->em->flush();
    }
}
