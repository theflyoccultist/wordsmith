# wordsmith

- i was working on some writing and suddenly thought about a project idea, where i could use the features of php, language that i am getting familiar with these days. the idea was a command line tool to look for synonyms of a specific word, along with a definition.

- the wordnet data files, which is used by this tool, is contained in the `dict/` directory, to minimize dependencies.

## features:

- support for nouns, verbs, adjectives and adverbs.
- use command line arguments to search for synonyms of a word. use the `-w=` or `--word=` argument when running the script, followed by the wanted word.

### optional arguments:

- `-d`: shows the definition for each set of synonyms as well.

## requirements:

- a recent version of php (php 8+)
- a recent version of composer

## tests:

- Test the application:

```bash
cd tests
./wordsmithtest

```

## run:

- configure & build:

- `composer install`

- in the project folder:

- `./bin/wordsmith -w=wonder <options>`


