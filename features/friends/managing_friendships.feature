@managing_friendships
Feature: Managing my friendships
  In order to control who I am friend with
  As a user
  I need to be able to manage my friendships

  Background:
    Given I am logged in as "john@example.com"
    And there is a user James
    And we are friends
    And there is a user Angela
    And we also are friends
    And there is a user Emma
    But we are not friends

  Scenario: Listing my friends
    When I want to manage my friendships
    Then I should see that I have 2 friends
    And I should see that I am friend with James and Angela

  Scenario: Listing received friend requests
    Given Emma wants us to be friends
    When I want to manage my friendships
    Then I should see that I have 1 friendship request
    And it should be from Emma

  Scenario: Displaying message when there are no received friendship requests
    When I want to manage my friendships
    Then I should see that I haven't got any received friendship requests

  Scenario: Listing sent friend requests
    Given I have sent a friendship request to Emma
    But she hasn't responded yet
    When I want to manage my friendships
    Then I should see that I have 1 sent friendship request
    And it should be for Emma

  Scenario: Displaying message when there are no sent friendship requests
    When I want to manage my friendships
    Then I should see that I haven't got any sent friendship requests

  Scenario: Cancelling a sent friendship request
    Given I have sent a friendship request to Emma
    But she hasn't responded yet
    When I want to manage my friendships
    And I want to cancel the friendship request
    Then I should be notified that the request is cancelled
    And I should see that I have 0 sent friendship requests

  Scenario: Accepting a friendship request
    Given Emma wants us to be friends
    When I want to manage my friendships
    And I accept her friendship request
    Then I should be notified that we've became friends
    And I shouldn't see the request anymore

  Scenario: Declining a friendship request
    Given Emma wants us to be friends
    When I want to manage my friendships
    And I decline her friendship request
    Then I should be notified that the request is removed
    And I shouldn't see the request anymore

  Scenario: Deleting existing friendships
    When I want to manage my friendships
    And I delete my friendship with James
    Then I should be notified that the friendship is deleted
    And I shouldn't see James in the list of friends anymore
