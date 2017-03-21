@thoughts
Feature: Deleting thoughts
  In order to take back what I have said
  As a user
  I should be able to delete my shared thoughts

  Background:
    Given I am logged in as "john@example.com"
    And I have shared a "What a beautiful day!" thought
    And there is a user James
    And he has shared a "Hello world!" thought

  Scenario: Deleting own thoughts
    Given I am browsing the thoughts
    When I delete the "What a beautiful day!" thought
    Then I shouldn't see it in the list of thoughts

  Scenario: Trying to delete other people's thoughts
    Given I am browsing the thoughts
    Then I should not be allowed to delete the "Hello world!" thought