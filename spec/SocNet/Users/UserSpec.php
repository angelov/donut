<?php

namespace spec\SocNet\Users;

use SocNet\Friendships\Friendship;
use SocNet\Friendships\FriendshipRequests\FriendshipRequest;
use SocNet\Places\City;
use SocNet\Thoughts\Thought;
use SocNet\Users\User;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\User\UserInterface;

class UserSpec extends ObjectBehavior
{
    const USER_ID = 'uuid value';
    const USER_NAME = 'John';
    const USER_EMAIL = 'john@example.com';
    const USER_PASSWORD = '123456';

    function let(City $city)
    {
        $this->beConstructedWith(self::USER_ID, self::USER_NAME, self::USER_EMAIL, self::USER_PASSWORD, $city);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
    }

    function it_implements_user_interface()
    {
        $this->shouldImplement(UserInterface::class);
    }

    function it_has_id_by_default()
    {
        $this->getId()->shouldReturn(self::USER_ID);
    }

    function it_has_mutable_id()
    {
        $this->setId('a');
        $this->getId()->shouldReturn('a');
    }

    function it_has_email_by_default()
    {
        $this->getEmail()->shouldReturn(self::USER_EMAIL);
    }

    function it_has_mutable_email()
    {
        $this->setEmail('john@example.com');
        $this->getEmail()->shouldReturn('john@example.com');
    }

    function it_has_password_by_default()
    {
        $this->getPassword()->shouldReturn(self::USER_PASSWORD);
    }

    function it_has_mutable_password()
    {
        $this->setPassword('123456');
        $this->getPassword()->shouldReturn('123456');
    }

    function it_has_name_by_default()
    {
        $this->getName()->shouldReturn(self::USER_NAME);
    }

    function it_has_mutable_name()
    {
        $this->setName('Dejan');
        $this->getName()->shouldReturn('Dejan');
    }

    function it_has_user_role_by_default()
    {
        $this->getRoles()->shouldReturn(['ROLE_USER']);
    }

    function it_returns_email_as_username()
    {
        $this->getUsername()->shouldReturn(self::USER_EMAIL);
    }

    function it_has_a_city_by_default(City $city)
    {
        $this->getCity()->shouldReturn($city);
    }

    function it_has_a_mutable_city(City $anotherCity)
    {
        $this->setCity($anotherCity);
        $this->getCity()->shouldReturn($anotherCity);
    }

    function it_has_no_thoughts_by_default()
    {
        $this->getThoughts()->shouldHaveCount(0);
    }

    function it_can_have_mulitple_thoughts(Thought $first, Thought $second)
    {
        $this->addThought($first);
        $this->addThought($second);

        $this->getThoughts()->shouldHaveCount(2);
    }

    function it_equals_users_with_same_id(User $anotherUser)
    {
        $anotherUser->getId()->willReturn($this->getId());

        $this->equals($anotherUser)->shouldReturn(true);
    }

    function it_does_not_equal_users_with_different_ids(User $anotherUser)
    {
        $anotherUser->getId()->willReturn('123');

        $this->equals($anotherUser)->shouldReturn(false);
    }

    function it_has_no_sent_friendship_requests_by_default()
    {
        $this->getSentFriendshipRequests()->shouldHaveCount(0);
    }

    function it_can_have_sent_friendship_requests(FriendshipRequest $request)
    {
        $this->addSentFriendshipRequest($request);

        $this->getSentFriendshipRequests()->shouldHaveCount(1);
    }

    function it_does_not_duplicate_sent_friendship_requests(FriendshipRequest $request, FriendshipRequest $another)
    {
        $this->addSentFriendshipRequest($request);
        $this->addSentFriendshipRequest($another);

        $this->addSentFriendshipRequest($request);

        $this->getSentFriendshipRequests()->shouldHaveCount(2);
    }

    function it_confirms_that_friendship_request_to_user_is_sent(FriendshipRequest $friendshipRequest, User $user)
    {
        $friendshipRequest->getToUser()->willReturn($user);
        $this->addSentFriendshipRequest($friendshipRequest);
        $user->equals($user)->willReturn(true);

        $this->hasSentFriendshipRequestTo($user)->shouldReturn(true);
    }

    function it_denies_that_friendship_request_to_user_is_sent(User $user)
    {
        $this->hasSentFriendshipRequestTo($user)->shouldReturn(false);
    }

    function it_has_no_received_friendship_requests_by_default()
    {
        $this->getReceivedFriendshipRequests()->shouldHaveCount(0);
    }

    function it_can_have_received_friendship_requests(FriendshipRequest $request)
    {
        $this->addReceivedFriendshipRequest($request);

        $this->getReceivedFriendshipRequests()->shouldHaveCount(1);
    }

    function it_does_not_duplicate_received_friendship_requests(FriendshipRequest $request, FriendshipRequest $anotherRequest)
    {
        $this->addReceivedFriendshipRequest($request);
        $this->addReceivedFriendshipRequest($anotherRequest);

        $this->addReceivedFriendshipRequest($request);

        $this->getReceivedFriendshipRequests()->shouldHaveCount(2);
    }

    function it_confirms_that_friendship_request_from_user_is_received(FriendshipRequest $request, User $user)
    {
        $request->getFromUser()->willReturn($user);
        $user->equals($user)->willReturn(true);

        $this->addReceivedFriendshipRequest($request);

        $this->hasReceivedFriendshipRequestFrom($user)->shouldReturn(true);
    }

    function it_denies_that_friendship_request_from_user_is_received(User $user)
    {
        $this->hasReceivedFriendshipRequestFrom($user)->shouldReturn(false);
    }

    function it_has_no_friends_by_default()
    {
        $this->getFriends()->shouldHaveCount(0);
    }

    function it_can_have_multiple_friends(User $user, User $anotherUser, Friendship $friendship, Friendship $another)
    {
        $friendship->getFriend()->willReturn($user);
        $this->addFriendship($friendship);

        $another->getFriend()->willReturn($anotherUser);
        $this->addFriendship($another);

        $this->getFriends()->shouldHaveCount(2);
        $this->getFriends()[0]->shouldBe($user);
    }

    function it_confirms_friendship_with_user(Friendship $friendship, User $user)
    {
        $friendship->getFriend()->willReturn($user);
        $user->equals($user)->willReturn(true);
        $this->addFriendship($friendship);

        $this->isFriendWith($user)->shouldReturn(true);
    }

    function it_denies_friendship_with_user(User $user)
    {
        $this->isFriendWith($user)->shouldReturn(false);
    }
}
