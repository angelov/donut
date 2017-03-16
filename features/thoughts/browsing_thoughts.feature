@thoughts
Feature: Browsing thoughts
  In order to see what people have said
  As a user
  I need to be able to browse the thoughts

  Background:
    Given I am logged in as "john@example.com"

  Scenario: Displaying thoughts from multiple users
    Given there is a user "Somebody Else" with email "somebody@example.com" and password "123456"
    And he has shared 7 thoughts
    And there is a user "Maria Williams" with email "maria@example.com" and password "123456"
    And she has shared 2 thoughts
    When I want to browse the thoughts
    Then I should see 7 thoughts from "Somebody Else"
    And I should see 2 thoughts from "Maria Williams"

  Scenario: Displaying only latest 10 thoughts on the first page
    Given there is a user "Somebody Else" with email "somebody@example.com" and password "123456"
    And he has shared 5 thoughts
    And there is a user "Maria Williams" with email "maria@example.com" and password "123456"
    And she has shared 10 thoughts
    When I want to browse the thoughts
    Then I should see 10 thoughts from "Maria Williams"
    But I should see 0 thoughts from "Somebody Else"

  Scenario: Displaying the rest of the thoughts on a second page
    Given there is a user "Somebody Else" with email "somebody@example.com" and password "123456"
    And he has shared 5 thoughts
    And there is a user "Maria Williams" with email "maria@example.com" and password "123456"
    And she has shared 10 thoughts
    When I want to browse the thoughts
    Then I should see 10 thoughts from "Maria Williams"
    And I should see 0 thoughts from "Somebody Else"
    But when I go to page 2
    Then I should see the rest 5 thoughts from "Somebody Else"