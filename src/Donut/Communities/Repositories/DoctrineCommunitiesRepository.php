<?php

namespace Angelov\Donut\Communities\Repositories;

use Angelov\Donut\Core\Exceptions\ResourceNotFoundException;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Communities\Community;

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

    /** @psalm-suppress MoreSpecificReturnType */
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

    public function all() : array
    {
        return $this->getBaseRepository()->findAll();
    }
}
