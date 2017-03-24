@browsing_communities
Feature: Listing the communities
  In order to see which communities I can join
  As a user
  I need to be able to see a list of existing communities

  Background:
    Given I am logged in as "john@example.com"
    And there is a community named "Music lovers"
    And there is a community named "Software Engineers"

  Scenario: Listing the existing communities
    When I want to browse the communities
    Then I should see 2 listed communities
#    And those communities should be "Music lovers" and "Software Engineers" @todo

  # @todo scenario for when there are no communities