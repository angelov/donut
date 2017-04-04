@browsing_communities
Feature: Listing the communities
  In order to see which communities I can join
  As a user
  I need to be able to see a list of existing communities

  Background:
    Given I am logged in as "john@example.com"

  Scenario: There are some communities
    Given there is a community named "Music lovers"
    And there is a community named "Software Engineers"
    When I want to browse the communities
    Then I should see 2 listed communities
    And those communities should be "Music lovers" and "Software Engineers"

  Scenario: There aren't any created communities
    Given nobody hasn't created any community yet
    When I want to browse the communities
    Then I should see a message that there aren't any existing communities