<?php

namespace spec\SocNet\Users\Commands;

use SocNet\Places\City;
use SocNet\Users\Commands\StoreUserCommand;
use PhpSpec\ObjectBehavior;

class StoreUserCommandSpec extends ObjectBehavior
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
        $this->shouldHaveType(StoreUserCommand::class);
    }

    function it_holds_the_id()
    {
        $this->getId()->shouldReturn(self::USER_ID);
    }

    function it_holds_the_user_name()
    {
        $this->getName()->shouldReturn(self::USER_NAME);
    }

    function it_holds_the_user_email()
    {
        $this->getEmail()->shouldReturn(self::USER_EMAIL);
    }

    function it_holds_the_user_password()
    {
        $this->getPassword()->shouldReturn(self::USER_PASSWORD);
    }

    function it_holds_the_city(City $city)
    {
        $this->getCity()->shouldReturn($city);
    }
}
