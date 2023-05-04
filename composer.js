{
  "name": "eugenewozniak/wp-cli-plugins-scanner",
  "description": "A WP-CLI package to scan WordPress plugins folder and compare with the official list.",
  "type": "wp-cli-package",
  "license": "MIT",
  "authors": [
    {
      "name": "Eugene Wozniak",
      "email": "wozniak88@gmail.com"
    }
  ],
  "require": {
    "php": ">=7.2",
    "wp-cli/wp-cli": "^2.5"
  },
  "autoload": {
    "files": [
      "command.php"
    ]
  }
}