@managing_friendships
Feature: Cancelling a sent friendship request
  # @todo better definition
  In order to change my mind about a sent friendship request
  As a user
  I need to be able to cancel the sent friendship request

  Background:
    Given I am logged in as "john@example.com"
    And there is a user Emma

  Scenario: Cancelling a sent friendship request
    Given I have sent a friendship request to Emma
    But she hasn't responded yet
    When I want to manage my friendships
    And I want to cancel the friendship request
    Then I should be notified that the request is cancelled
    And I should see that I have 0 sent friendship requests