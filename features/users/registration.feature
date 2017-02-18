@users
Feature: User registration
  In order to use the social network
  As a visitor
  I need to create an user account

  Scenario: Registering with valid information
    Given I want to create a new user account
    And I specify the name as "John Smith"
    And I specify the email as "john@example.com"
    And I specify the password as "johnny11"
    And I create the account
    Then I should be notified that my user account has been successfully created
    But I should not be logged in
