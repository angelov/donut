<?php

namespace spec\SocNet\Core\UuidGenerator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\UuidGenerator;
use SocNet\Core\UuidGenerator\DoctrineUuidGeneratorAdapter;
use PhpSpec\ObjectBehavior;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;

class DoctrineUuidGeneratorAdapterSpec extends ObjectBehavior
{
    function let(EntityManager $entityManager, UuidGenerator $uuidGenerator)
    {
        $this->beConstructedWith($entityManager, $uuidGenerator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DoctrineUuidGeneratorAdapter::class);
    }

    function it_is_uuid_generator()
    {
        $this->shouldImplement(UuidGeneratorInterface::class);
    }

    function it_passes_calls_to_base_generator(EntityManager $entityManager, UuidGenerator $uuidGenerator)
    {
        $uuidGenerator->generate($entityManager, null)->shouldBeCalled()->willReturn('uuid value');

        $this->generate()->shouldReturn('uuid value');
    }
}
