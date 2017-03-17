@viewing_communities
Feature: Viewing a community
  In order to find users with similar interests
  As a user
  I need to be able to view a community's details

  Background:
    Given I am logged in as "john@example.com"
    And there is a community named "PHP Macedonia" and described as "PHP developers from Macedonia"
    And I have joined it
    And there is a user "Maria" with email "maria@example.com" and password "123456"
    And there is a user "James" with email "james@example.com" and password "123456"
    And he has also joined it

  Scenario: Displaying basic information about the community
    When I want to view the "PHP Macedonia" community
    Then I should see its description
    And I should see its author
    And I should see its creation date

  Scenario: Displaying a list of members of the community
    When I want to view the "PHP Macedonia" community
    Then I should see a list of its members
    And I should be part of it
    And it should have 2 members

  Scenario: Displaying basic information about the community to users who hasn't joined the community
    Given there is a community named "Pearl Jam Fans"
    And I haven't joined it
    When I want to view it
    Then I should see its description
    And I should see its author
    And I should see its creation date

  Scenario: Hiding the members list from users who hasn't joined the community
    Given there is a community named "Pearl Jam Fans"
    And I haven't joined it
    When I want to view it
    Then I shouldn't see a list of its members