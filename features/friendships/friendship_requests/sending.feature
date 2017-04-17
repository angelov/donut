@managing_friendships
Feature: Sending friendship request
  In order to become a friend with somebody
  As a user
  I need to be able to send a friendship requests to them

  Background:
    Given I am logged in as "john@example.com"
    And there is a user Emma

  Scenario: Sending a friendship request
    Given I am browsing the users
    When I want to be friends with Emma
    Then I should be notified that a friendship request is sent