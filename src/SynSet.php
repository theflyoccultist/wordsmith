<?php

namespace Wordsmith;

enum PartOfSpeech: string
{
  case Noun = "noun";
  case Verb = "verb";
  case Adjective = "adj";
  case Adverb = "adv";
}

final readonly class SynSet
{
  public function __construct(
    public PartOfSpeech $partOfSpeech,
    public array $words,
    public string $definition,
  ) {}
}

function parseSynsetWords(array $fields): array
{
  $parsedWords = array();
  $sizeOfWordsArr = intval($fields[3]);

  $i = 4;
  while ($sizeOfWordsArr > 0) {
    $parsedWords[] =  $fields[$i];
    $sizeOfWordsArr--;
    $i += 2;
  }

  return $parsedWords;
}

function parseSynsetDefinition(array $fields): string
{
  $pos = array_search('|', $fields);
  $text = array_slice($fields, $pos + 1);
  $definition = implode(" ", $text);
  return $definition;
}
