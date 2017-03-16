@login
Feature: User registration
  In order to use the social network
  As a visitor
  I need to log in to the application

  Background:
    Given I am "John Smith"
    And I am registered with email "john@example.com" and password "123456"

  Scenario: Signing in with valid credentials
    When I want to log in
    And I specify the email as "john@example.com"
    And I specify the password as "123456"
    And I try to log in
    Then I should be logged in