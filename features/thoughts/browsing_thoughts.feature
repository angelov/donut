@thoughts
Feature: Browsing thoughts
  In order to see what people have said
  As a user
  I need to be able to browse the thoughts

  Background:
    Given I am logged in as "john@example.com"
    And there is a user James
    And there is a user Maria

  Scenario: Displaying thoughts from multiple users
    Given James has shared 7 thoughts
    And Maria has shared 2 thoughts
    When I want to browse the thoughts
    Then I should see 7 thoughts from James
    And I should see 2 thoughts from Maria

  Scenario: Displaying only latest 10 thoughts on the first page
    Given James has shared 5 thoughts
    And Maria has shared 10 thoughts
    When I want to browse the thoughts
    Then I should see 10 thoughts from Maria
    But I should see 0 thoughts from James

  Scenario: Displaying the rest of the thoughts on a second page
    Given James has shared 5 thoughts
    And Maria has shared 10 thoughts
    When I want to browse the thoughts
    Then I should see 10 thoughts from Maria
    And I should see 0 thoughts from James
    But when I go to page 2
    Then I should see the rest 5 thoughts from James
