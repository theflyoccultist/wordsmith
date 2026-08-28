# Wordsmith

- I was working on some writing and suddenly thought about a project idea, where I could use the features of PHP, language that I am getting familiar with these days. The idea was a command line tool to look for synonyms of a specific word, along with a definition.

- The WordNet data files, which is used by this tool, is contained in the `dict/` directory, to minimize dependencies.

## Features:

- Support for Nouns, Verbs, Adjectives and Adverbs.
- Use command line arguments to search for synonyms of a word. Use the `-w=` or `--word=` argument when running the script, followed by the wanted word.

## Requirements:

- A recent version of PHP (PHP 8.2~)

## Run:

- `php src/main.php -w=wonder <Options>`

- Then, simply enter the word that you want informations about.

#### Optional Arguments:

- `-d`: Shows the definition for each set of synonyms as well.

## Upcoming Tasks:

- Support for French (currently it only supports English).

