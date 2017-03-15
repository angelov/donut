@users
Feature: User registration
  In order to use the social network
  As a visitor
  I need to create an user account

  Scenario: Registering with valid information
    When I want to create a new user account
    And I specify the name as "John Smith"
    And I specify the email as "john@example.com"
    And I specify the password as "johnny11"
    And I confirm the password
    And I create the account
    Then I should be notified that my user account has been successfully created
    But I should not be logged in

  Scenario: Trying to register without specified name
    When I want to create a new user account
    And I specify the email as "john@example.com"
    And I specify the password as "johnny11"
    And I confirm the password
    But I don't specify the name
    And I try to create the account
    Then I should be notified that the name is required
    And I should not be logged in

  Scenario: Trying to register without specified email
    When I want to create a new user account
    And I specify the name as "John Smith"
    And I specify the password as "johnny11"
    And I confirm the password
    But I don't specify the email
    And I try to create the account
    Then I should be notified that the email is required
    And I should not be logged in

  Scenario: Trying to register without specified password
    When I want to create a new user account
    And I specify the name as "John Smith"
    And I specify the email as "john@example.com"
    But I don't specify the password
    And I try to create the account
    Then I should be notified that the password is required
    And I should not be logged in

  Scenario: Trying to register with short password
    When I want to create a new user account
    And I specify the password as "12345"
    And I try to create the account
    Then I should be notified that the password is too short
    And I should not be logged in

  Scenario: Trying to register without confirming the specified password
    When I want to create a new user account
    And I specify the name as "John Smith"
    And I specify the email as "john@example.com"
    And I specify the password as "johnny11"
    But I don't confirm the password
    And I try to create the account
    Then I should be notified that the password must be confirmed
    And I should not be logged in

  Scenario: Trying to register with email that is already in use
    Given there is a user "John Smith" with email "john@example.com" and password "johnny11"
    When I want to create a new user account
    And I specify the email as "john@example.com"
    And I try to create the account
    Then I should be notified that the specified email is already in use
    And I should not be logged in
