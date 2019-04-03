# This file contains a user story for demonstration only.
# Learn how to get started with Behat and BDD on Behat's website:
# http://behat.org/en/latest/quick_start.html

Feature:
  In order to prove that the Behat Symfony extension is correctly installed
  As a user
  I want to have a demo scenario


# scénario de ce qui est attendu
  Scenario: A user can access to home page
    When a user sends a request to "/"
    Then the status code should be "200"

  Scenario: A anonymous user cannot access to add car page
    When a user sends a request to "/livre/add"
    Then the status code should be "302"
    Then i should be redirected to "/registration"

  Scenario: A anonymous user can see an announcement
    When a user sends a request to "/show/77"
    Then the status code should be "200"
    Then the page should contains "Votre livre : J'ajoute un livre en mode admin"
    Then the page should contains "Ecrit par Inconnu"






