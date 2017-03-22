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
        $shared = $this->storage->get('thought_content');

        $found = $page->find('css', sprintf('pre:contains("%s")', $shared));

        if (!$found) {
            throw new \Exception();
        }
    }

    /**
     * @Then I shouldn't see it in the list of (latest) thoughts
     */
    public function iShouldnTSeeItInTheListOfThoughts()
    {
        $content = $this->storage->get('deleted_thought_content');
        $found = $this->session->getPage()->find('css', sprintf('pre:contains("%s")', $content));

        if ($found) {
            throw new \Exception();
        }
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

    /**
     * @When I want to browse the thoughts
     * @Given I am browsing the thoughts
     */
    public function iWantToBrowseTheThoughts()
    {
        $url = $this->router->generate('app.thoughts.index');

        $this->session->getDriver()->visit($url);
    }

    /**
     * @Then I should see (the rest) :count thoughts from :name
     */
    public function iShouldSeeThoughtsFrom(int $count, string $name)
    {
        $thoughts = $this->session->getPage()->findAll('css', sprintf('.thought:contains("by %s")', $name));
        $counted = count($thoughts);

        if ($counted !== $count) {
            throw new \Exception(sprintf(
                'Counted %d instead of %d',
                $counted,
                $count
            ));
        }
    }

    /**
     * @When I delete the :content thought
     */
    public function iDeleteTheThought(string $content)
    {
        $thought = $this->session->getPage()->find('css', sprintf('pre:contains("%s")', $content));

        $thought->getParent()->pressButton('delete');

        $this->storage->set('deleted_thought_content', $content);
    }

    /**
     * @Then I should not be allowed to delete the :content thought
     */
    public function iShouldNotBeAllowedToDeleteTheThought(string $content)
    {
        $thought = $this->session->getPage()->find('css', sprintf('pre:contains("%s")', $content));
        $parent = $thought->getParent();

        if ($parent->hasButton('delete')) {
            throw new \Exception('Found a forbidden delete button.');
        }
    }
}
