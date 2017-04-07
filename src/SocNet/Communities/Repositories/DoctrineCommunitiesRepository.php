<?php

namespace SocNet\Communities\Repositories;

use AppBundle\Exceptions\ResourceNotFoundException;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use SocNet\Communities\Community;

class DoctrineCommunitiesRepository implements CommunitiesRepositoryInterface
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

    public function find(string $id): Community
    {
        $found = $this->getBaseRepository()->find($id);

        if (!$found) {
            throw new ResourceNotFoundException();
        }

        return $found;
    }

    private function getBaseRepository() : ObjectRepository
    {
        return $this->em->getRepository(Community::class);
    }
}