<?php

namespace Angelov\Donut\Thoughts\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use Angelov\Donut\Thoughts\Thought;

class DoctrineThoughtsRepository implements ThoughtsRepositoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function store(Thought $thought): void
    {
        $this->em->persist($thought);
        $this->em->flush();
    }

    public function destroy(Thought $thought) : void
    {
        $this->em->remove($thought);
        $this->em->flush();
    }
}
