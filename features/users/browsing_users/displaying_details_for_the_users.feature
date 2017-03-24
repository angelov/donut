@browsing_users
Feature: Displaying details for the users
  In order to learn more about the listed users
  As a user
  I need to be able to see a basic information for each of them

  Background:
    Given I am John
    And I am logged in as "john@example.com"
    And there is a user Dejan
    And we are friends
    And there is a user James with email "james@example.com"
    And he has shared 3 thoughts
    And he is friend with Dejan
    And we are not friends

  Scenario: Displaying details for the users
    When I want to browse the users
    Then I should see that James has shared 3 thoughts
    And I should see that his email is "james@example.com"
    And I should see that he has 1 friend
    And I should see that we have 1 mutual friend
    And that friend should be Dejan
