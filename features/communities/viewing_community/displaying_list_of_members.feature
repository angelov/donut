@viewing_communities
Feature: List of members
  In order to see who is part of the community
  As a user
  I need to be able to view a list of community members

  Background:
    Given I am logged in as "john@example.com"
    And there is a community named "PHP Macedonia"
    And I have joined it
    And there is a user Maria
    And he has also joined it
    And there is a user James

  Scenario: Displaying a list of members of the community
    When I want to view the "PHP Macedonia" community
    Then I should see a list of its members
    And I should be part of it
    And it should have 2 members

  # @todo when there are no members

  Scenario: Hiding the members list from users who hasn't joined the community
    Given there is a community named "Pearl Jam Fans"
    And I haven't joined it
    When I want to view it
    Then I shouldn't see a list of its members