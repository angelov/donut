<?php

namespace SocNet\Core\UuidGenerator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\UuidGenerator;

class DoctrineUuidGeneratorAdapter implements UuidGeneratorInterface
{
    private $entityManager;
    private $baseGenerator;

    public function __construct(EntityManager $entityManager, UuidGenerator $baseGenerator)
    {
        $this->entityManager = $entityManager;
        $this->baseGenerator = $baseGenerator;
    }

    public function generate(): string
    {
        return $this->baseGenerator->generate($this->entityManager, null);
    }
}
