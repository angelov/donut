@joining_community
Feature: Approving new members
  In order for member to join a community
  As a member/owner of the community
  I need to be able to approve their membership request

  Background:
    Given there is a user John
    And there is a user Dejan
    And he is a site administrator
    And there is a user Emma
    And she has created the "PHP Macedonia" community
    And John has requested to join it

  Scenario: Approving by administrator when joining policy is "Owner approval"
    Given only the community owner can approve new "PHP Macedonia" members
    And I am logged in as Dejan
    And I am not member of the "PHP Macedonia" community
    When I am on the "PHP Macedonia" community page
    And I approve John's membership request
    Then John should be added as a member to the community

  Scenario: Approving by administrator when joining policy is "Members approval"
    Given any existing members can approve new "PHP Macedonia" members
    And I am logged in as Dejan
    And I am not member of the "PHP Macedonia" community
    When I am on the "PHP Macedonia" community page
    And I approve John's membership request
    Then John should be added as a member to the community

  Scenario: Approving by owner when joining policy is "Owner approval"
    Given only the community owner can approve new "PHP Macedonia" members
    And I am logged in as Emma
    When I am on the "PHP Macedonia" community page
    And I approve John's membership request
    Then John should be added as a member to the community

  Scenario: Trying to approve by regular member when joining policy is "Owner approval"
    Given only the community owner can approve new "PHP Macedonia" members
    And there is a user Angela
    And she has joined the "PHP Macedonia" community
    And I am logged in as Angela
    When I am on the "PHP Macedonia" community page
    Then I should not be able to approve John's membership request

  Scenario: Approving by regular member when joining policy is "Members approval"
    Given any existing members can approve new "PHP Macedonia" members
    And there is a user Angela
    And she has joined the "PHP Macedonia" community
    And I am logged in as Angela
    When I am on the "PHP Macedonia" community page
    And I approve John's membership request
    Then John should be added as a member to the community
