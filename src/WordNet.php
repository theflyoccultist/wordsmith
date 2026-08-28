<?php

namespace Wordsmith;

require 'SynSet.php';

use Exception;

final class WordNet
{
  function findinDataFile(array $offsets, PartOfSpeech $partOfSpeech): array
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
            $wordsArr[] = new SynSet(
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

  function findinIndexFile(string $inputWord, PartOfSpeech $partOfSpeech)
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

          $results = $this->findinDataFile($offsets, $partOfSpeech);
          $this->printData($inputWord, $results);
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

  function printData(string $inputWord, array $wordsArr)
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

  function printResults(string $inputWord)
  {
    foreach (PartOfSpeech::cases() as $part) {
      echo $part->value . PHP_EOL;
      $this->findinindexfile($inputWord, $part);
    }
  }

  function printHelp()
  {
    echo "Wordsmith is a command line tool for finding synonyms of a specific word." . PHP_EOL;
    echo "Usage: -w=wonder [options]" . PHP_EOL;
    echo "Options:" . PHP_EOL;
    echo "\t -d, --def \t Displays a detailed definition for each group of synonyms" . PHP_EOL;
    echo "\t -h, --help \t Displays this help message" . PHP_EOL;
  }
}
