<?php

namespace AppBundle\FeatureContexts;

use Behat\Behat\Context\Context;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use Symfony\Component\Routing\RouterInterface;

class ThoughtsContext implements Context
{
    private $session;
    private $router;
    private $storage;

    public function __construct(Session $session, RouterInterface $router, Storage $storage)
    {
        $this->session = $session;
        $this->router = $router;
        $this->storage = $storage;
    }

    /**
     * @When I want to share a thought
     */
    public function iWantToShareAThought()
    {
        $url = $this->router->generate('app.thoughts.index');

        $this->session->getDriver()->visit($url);
    }

    /**
     * @Given I specify its content as :content
     */
    public function iSpecifyItsContentAs($content)
    {
        $this->session->getPage()->fillField('Content', $content);

        $this->storage->set('thought_content', $content);
    }

    /**
     * @Given I specify its content as something longer than :length characters
     */
    public function iSpecifyItsContentAsSomethingLongerThanCharacters(int $length)
    {
        $list = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ<>?:"{}!@#$%^&';
        $list = str_shuffle($list);

        if (strlen($list) < $length+1) {
            $list .= $list;
        }

        $content = substr($list, 0, $length+1);

        $this->iSpecifyItsContentAs($content);
    }

    /**
     * @When I try to share it
     */
    public function iTryToShareIt()
    {
        $this->session->getPage()->pressButton('Submit');
    }

    /**
     * @Then I should see it in the list of latest thoughts
     */
    public function iShouldSeeItInTheListOfLatestThoughts()
    {
        $page = $this->session->getPage();
        $thoughts = $page->findAll('css', 'pre');
        $shared = $this->storage->get('thought_content');

        /** @var NodeElement $thought */
        foreach ($thoughts as $thought) {
            if ($thought->getText() === $shared) {
                return;
            }
        }

        throw new \Exception();
    }

    /**
     * @Then I should be notified that the maximum length is :length characters
     */
    public function iShouldBeNotifiedThatTheMaximumLengthIsCharacters(int $length)
    {
        $found = $this->session->getPage()->hasContent(sprintf(
            'Thoughts can\'t be longer than %d characters.',
            $length
        ));

        if (!$found) {
            throw new \Exception();
        }
    }
}
