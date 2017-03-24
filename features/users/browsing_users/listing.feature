@browsing_users
Feature: Listing the users
  In order to find other users
  As a user
  I need to be able to see a list of all users

  Background:
    Given I am John
    And I am logged in as "john@example.com"
    And there are users Dejan, James and Emma

  Scenario: Listing the users
    When I want to browse the users
    Then I should see 4 users in the list
    And those users should be John, Dejan, James and Emma