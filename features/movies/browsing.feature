@browsing_movies
Feature: Listing the movies with filters
  In order to find new movies
  As a user
  I need to be able to see a list filtered list of movies

  Background:
    Given I am logged in as "john@example.com"
    And there are the following genres:
      | Title  |
      | Comedy |
      | Horror |
      | Action |
    And there are the following movies
      | Title                             | Genres                 | Year |
      | Funny movie                       | Comedy                 | 1960 |
      | Funny movie with guns             | Comedy, Action         | 1970 |
      | Movie with fighting               | Action                 | 2005 |
      | Modern movie with guns and clowns | Comedy, Action         | 2017 |
      | Movie that scares me              | Horror                 | 1980 |
      | Scary, but funny movie            | Horror, Comedy         | 1992 |
      | Scary, funny movie with fighting  | Horror, Comedy, Action | 1995 |

  Scenario: Browsing without chosen filters
    When I want to browse the movies
    And I don't specify any filters
    Then I should see 7 listed movies

  Scenario: Browsing movies for specified genre
    When I want to browse the movies
    And I choose the Comedy genre
    Then I should see 5 listed movies
    And those movies should be the following
      | Title                             |
      | Funny Movie                       |
      | Funny movie with guns             |
      | Modern movie with guns and clowns |
      | Scary, but funny movie            |
      | Scary, funny movie with fighting  |

  Scenario: Browsing movies from multiple genres
    When I want to browse the movies
    And I choose the Comedy and Action genres
    Then I should see 3 listed movies
    And those movies should be the following
      | Title                             |
      | Funny movie with guns             |
      | Modern movie with guns and clowns |
      | Scary, funny movie with fighting  |

  # todo add more scenarios