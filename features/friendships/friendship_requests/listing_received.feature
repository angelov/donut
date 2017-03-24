@managing_friendships
Feature: Listing received friendship requests
  In order to see who wants to be a friend with me
  As a user
  I need to be able to see a list of received friendship requests

  Background:
    Given I am logged in as "john@example.com"

  Scenario: Somebody has sent me a friendship request
    Given there is a user Emma
    And Emma wants us to be friends
    When I want to manage my friendships
    Then I should see that I have 1 friendship request
    And it should be from Emma

  Scenario: Nobody has sent me a friendship request
    When I want to manage my friendships
    Then I should see that I haven't got any received friendship requests