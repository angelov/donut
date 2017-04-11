<?php

namespace spec\SocNet\Users\Security\CurrentUserResolver;

use SocNet\Users\Security\CurrentUserResolver\CurrentUserResolver;
use SocNet\Users\User;
use SocNet\Users\Security\CurrentUserResolver\CurrentUserResolverInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class CurrentUserResolverSpec extends ObjectBehavior
{
    function let(TokenStorageInterface $tokenStorage)
    {
        $this->beConstructedWith($tokenStorage);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CurrentUserResolver::class);
    }

    function it_implements_current_user_resolver_interface()
    {
        $this->shouldImplement(CurrentUserResolverInterface::class);
    }

    function it_throws_exception_when_no_user_is_authenticated(TokenStorageInterface $tokenStorage)
    {
        $tokenStorage->getToken()->willReturn(null);

        $this->shouldThrow(AuthenticationException::class)->during('getUser');
    }

    function it_throws_exception_when_user_is_logged_anonymously(TokenStorageInterface $tokenStorage, TokenInterface $token)
    {
        $tokenStorage->getToken()->willReturn($token);
        $token->getUser()->willReturn('something');

        $this->shouldThrow(AuthenticationException::class)->during('getUser');
    }

    function it_return_current_user(TokenStorageInterface $tokenStorage, TokenInterface $token, User $user)
    {
        $tokenStorage->getToken()->willReturn($token);
        $token->getUser()->willReturn($user);

        $this->getUser()->shouldReturn($user);
    }
}
