<?php

namespace Angelov\Donut\Behat\Pages\Communities;

use Angelov\Donut\Behat\Pages\Page;

class CreateCommunityPage extends Page
{
    protected function getRoute(): string
    {
        return 'app.communities.create';
    }

    public function specifyName(string $name) : void
    {
        $this->getDocument()->fillField('Name', $name);
    }

    public function specifyDescription(string $description) : void
    {
        $this->getDocument()->fillField('Description', $description);
    }

    public function create() : void
    {
        $this->getDocument()->pressButton('Submit');
    }
}
