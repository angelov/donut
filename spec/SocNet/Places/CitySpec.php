<?php

namespace spec\SocNet\Places;

use SocNet\Places\City;
use PhpSpec\ObjectBehavior;
use SocNet\Users\User;

class CitySpec extends ObjectBehavior
{
    const CITY_ID = 'uuid value';
    const CITY_NAME = 'Skopje';

    function let()
    {
        $this->beConstructedWith(self::CITY_ID, self::CITY_NAME);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(City::class);
    }

    function it_has_id_by_default()
    {
        $this->getId()->shouldReturn(self::CITY_ID);
    }

    function it_has_mutable_id()
    {
        $this->setId('a');
        $this->getId()->shouldReturn('a');
    }

    function it_has_a_name_by_default()
    {
        $this->getName()->shouldReturn(self::CITY_NAME);
    }

    function it_has_mutable_name()
    {
        $newName = 'Bitola';

        $this->setName($newName);
        $this->getName()->shouldReturn($newName);
    }

    function it_has_no_residents_by_default()
    {
        $this->getResidents()->shouldHaveCount(0);
    }
    
    function it_can_have_multiple_residents(User $first, User $second)
    {
        $first->setCity($this)->shouldBeCalled();
        $second->setCity($this)->shouldBeCalled();

        $this->addResident($first);
        $this->addResident($second);

        $this->getResidents()->shouldHaveCount(2);
    }

    function it_does_not_duplicate_residents(User $first)
    {
        $first->setCity($this)->shouldBeCalledTimes(1);

        $this->addResident($first);
        $this->addResident($first);

        $this->getResidents()->shouldHaveCount(1);
    }
}
