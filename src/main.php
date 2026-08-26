<?php

enum PartOfSpeech
{
  case Noun;
  case Verb;
  case Adjective;
  case Adverb;
}

final readonly class Synset
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

function findInDataFile(array $offsets): array
{
  $dataFile = __DIR__ . '/../dict/data.noun';

  try {
    $fp = fopen($dataFile, "r");
    if (!$fp) {
      throw new Exception("Error opening the file.");
    }

    $wordsArr = array();

    while (($line = fgets($fp)) !== false) {
      $fields = preg_split('/\s+/', trim($line));
      foreach ($offsets as $offset) {
        if ($fields[0] == $offset) {
          $wordsArr[] = new Synset(
            partOfSpeech: PartOfSpeech::Noun,
            words: parseSynsetWords($fields),
            definition: parseSynsetDefinition($fields),
          );
        }
      }
    }

    fclose($fp);
    return $wordsArr;
  } catch (Exception $e) {
    echo "An error occured: " . $e->getMessage();
    return [];
  }
}

function formatData(string $inputWord, array $wordsArr)
{
  $i = 1;
  foreach ($wordsArr as $word) {
    echo $i++ . ". " . $word->definition . PHP_EOL;

    foreach ($word->words as $w) {
      if ($w !== $inputWord) {
        echo "\t- " . $w . PHP_EOL;
      }
    }
  }
}

function readIndexFile(string $inputWord)
{
  $indexFile = __DIR__ . '/../dict/index.noun';
  $found = false;

  try {
    $f = fopen($indexFile, "r");
    if (!$f) {
      throw new Exception("Error opening the file.");
    }

    while (($line = fgets($f)) !== false) {
      $fields = preg_split('/\s+/', trim($line));

      if (($fields[0] ?? null) === $inputWord) {
        $numofSynsets = $fields[2];
        $offsets = array_slice($fields, -$numofSynsets);

        echo "Word $inputWord was found\n";
        echo $line . "\n";

        $results = findInDataFile($offsets);
        formatData($inputWord, $results);
        $found = true;
        break;
      }
    }

    if (!$found) {
      echo "Word $inputWord was not found\n";
    }

    fclose($f);
  } catch (Exception $e) {
    echo "An error occured: " . $e->getMessage();
  }
}


$inputWord = readline('Enter a word: ');
readIndexfile($inputWord);
