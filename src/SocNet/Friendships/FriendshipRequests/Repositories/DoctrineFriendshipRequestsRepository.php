<?php

namespace SocNet\Friendships\FriendshipRequests\Repositories;

use Doctrine\ORM\EntityManagerInterface;
use SocNet\Core\Exceptions\ResourceNotFoundException;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;

class DoctrineFriendshipRequestsRepository implements FriendshipRequestsRepositoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function store(FriendshipRequest $request): void
    {
        $this->em->persist($request);
        $this->em->flush();
    }

    public function destroy(FriendshipRequest $request): void
    {
        $this->em->remove($request);
        $this->em->flush();
    }

    /**
     * @throws ResourceNotFoundException
     * @todo write phpunit test
     */
    public function find(string $id) : FriendshipRequest
    {
        $found = $this->em->find(FriendshipRequest::class, $id);

        if (!$found) {
            throw new ResourceNotFoundException();
        }

        return $found;
    }
}
