<?php

enum PartOfSpeech: string
{
  case Noun = "noun";
  case Verb = "verb";
  case Adjective = "adj";
  case Adverb = "adv";
}

final readonly class Synset
{
  public function __construct(
    public PartOfSpeech $partOfSpeech,
    public array $words,
    public string $definition,
  ) {}
}

$printDefinitions = false;

$short_options = "w:d";
$long_options = ["word:", "def"];
$options = getopt($short_options, $long_options);
if (!$options) {
  echo "Wordsmith is a command line tool for finding synonyms of a specific word." . PHP_EOL;
  echo "Usage: -w=wonder [options]" . PHP_EOL;
  echo "The options are:" . PHP_EOL;
  echo "\t -d \t Displays a detailed definition for each group of synonyms" . PHP_EOL;

  return;
}

if (isset($options["w"]) || isset($options["word"])) {
  $inputWord = isset($options["w"]) ? $options["w"] : $options["word"];
}

if (isset($options["d"]) || isset($options["def"])) {
  $printDefinitions = true;
}

foreach (PartOfSpeech::cases() as $part) {
  echo $part->value . PHP_EOL;
  readIndexfile($inputWord, $part);
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

function findInDataFile(array $offsets, PartOfSpeech $partOfSpeech): array
{
  $dataFile = __DIR__ . '/../dict/data.' . $partOfSpeech->value;

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
            partOfSpeech: $partOfSpeech,
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
  global $printDefinitions;
  $i = 1;
  foreach ($wordsArr as $word) {
    if ($printDefinitions) {
      echo $i++ . ". " . $word->definition . PHP_EOL;
    }

    foreach ($word->words as $w) {
      if ($w !== $inputWord) {
        echo "\t- " . $w . PHP_EOL;
      }
    }
  }
}

function readIndexFile(string $inputWord, PartOfSpeech $partOfSpeech)
{
  $indexFile = __DIR__ . '/../dict/index.' . $partOfSpeech->value;
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
        /* echo $line . "\n"; */

        $results = findInDataFile($offsets, $partOfSpeech);
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
