@thoughts
Feature: Sharing thoughts
  In order to express my feelings
  As a user
  I need to be able to share my thoughts

  Background:
    Given I am logged in as "john@example.com"

  Scenario: Sharing a valid thought
    When I want to share a thought
    And I specify its content as "Hello world!"
    And I try to share it
    Then I should see it in the list of latest thoughts

  Scenario: Sharing a thought without a content
    When I want to share a thought
    But I don't specify its content
    And I try to share it
    Then I should be notified that the thought must have content

  Scenario: Trying to share a thought longer than allowed
    When I want to share a thought
    And I specify its content as something longer than 140 characters
    And I try to share it
    Then I should be notified that the maximum length is 140 characters