@thoughts
Feature: Browsing thoughts
  In order to see what people have said
  As a user
  I need to be able to browse the thoughts

  Background:
    Given I am logged in as "john@example.com"
    And there is a user James
    And there is a user Maria

  Scenario: Multiple friends have shared thoughts
    Given I am friend with James
    And I am friend with Maria
    And James has shared 7 thoughts
    And Maria has shared 2 thoughts
    When I want to browse the thoughts
    Then I should see 7 thoughts from James
    And I should see 2 thoughts from Maria

  Scenario: Displaying only latest 10 thoughts on the first page
    Given I am friend with James
    And I am friend with Maria
    And James has shared 5 thoughts
    And Maria has shared 10 thoughts
    When I want to browse the thoughts
    Then I should see 10 thoughts from Maria
    But I should see 0 thoughts from James

  Scenario: Displaying the rest of the thoughts on a second page
    Given I am friend with James
    And I am friend with Maria
    And James has shared 5 thoughts
    And Maria has shared 10 thoughts
    When I want to browse the thoughts
    Then I should see 10 thoughts from Maria
    And I should see 0 thoughts from James
    But when I go to the second page
    Then I should see the rest 5 thoughts from James

  Scenario: There are thoughts from people who aren't friends of mine
    Given I am friend with James
    But I am not friend with Maria
    And James has shared 5 thoughts
    And Maria has shared 10 thoughts
    When I want to browse the thoughts
    Then I should see 5 thoughts from James
    And I should see 0 thoughts from Maria

  Scenario: Both I and my friends have shared thoughts
    Given I am friend with James
    And I am friend with Maria
    And James has shared 2 thoughts
    And Maria has shared 2 thoughts
    And I have shared 2 thoughts
    When I want to browse the thoughts
    Then I should see 2 thoughts from James
    And I should see 2 thoughts from Maria
    And I should see the 2 thoughts of mine