@browsing_users
Feature: Browsing users
  In order to find other users
  As a user
  I need to be able to browse the users list

  Background:
    Given I am John
    And I am logged in as "john@example.com"
    And there is a user Dejan
    And he has shared 2 thoughts
    And we are friends
    And there is a user James with email "james@example.com"
    And he has shared 3 thoughts
    And he is friend with Dejan
    And we are not friends
    And there is a user Emma
    And she is friend with Dejan
    And she has shared 0 thoughts

  Scenario: Listing the users
    When I want to browse the users
    Then I should see 4 users in the list
    And those users should be John, Dejan, James and Emma

  Scenario: Displaying details for the users
    When I want to browse the users
    Then I should see that James has shared 3 thoughts
    And I should see that his email is "james@example.com"
    And I should see that he has 1 friend
    And I should see that we have 1 mutual friend
    And that friend should be Dejan
