# Wordsmith

- I was working on some writing and suddenly thought about a project idea, where I could use the features of PHP, language that I am getting familiar with these days. The idea was a command line tool to look for synonyms of a specific word, along with a definition.

- The WordNet data files, which is used by this tool, is contained in the `dict/` directory, to minimize dependencies.

## Features:

- Support for Nouns, Verbs, Adjectives and Adverbs.

## Requirements:

- A recent version of PHP (PHP 8.2~)

## Run:

- `php src/main.php -w=wonder`

- Then, simply enter the word that you want informations about.

## Upcoming Tasks:

- Support for French (currently it only supports English).

- Passing command line arguments is now supported. Think of what different functionalities the CLI can have.

