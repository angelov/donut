@managing_friendships
Feature: Managing my friendships
  In order to control who I am friend with
  As a user
  I need to be able to manage my friendships

  Background:
    Given I am logged in as "john@example.com"
    And there is a user James with email "james@example.com" and password "123456"
    And we are friends
    And there is a user Angela with email "angela@example.com" and password "123456"
    And we also are friends
    And there is a user Emma with email "emma@example.com" and password "123456"
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

  #todo scenario for when I haven't received any requests

  Scenario: Listing sent friend requests
    Given I have sent a friendship request to Emma
    But she hasn't responded yet
    When I want to manage my friendships
    Then I should see that I have 1 sent friendship request
    And it should be for Emma

  #todo scenario for when I haven't sent any requests