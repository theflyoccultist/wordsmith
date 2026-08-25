<?php
/**/
/* enum PartOfSpeech */
/* { */
/*   case Noun; */
/*   case Verb; */
/*   case Adjective; */
/*   case Adverb; */
/* } */
/**/
/* final readonly class Synset */
/* { */
/*   public function __construct( */
/*     public array $words, */
/*     public PartOfSpeech $partOfSpeech, */
/*     public string $definition, */
/*   ) {} */
/* } */
/**/
/* $synset = new Synset( */
/*   words: ["pwatatouille", "pwatpwat", "kuromi", "pwatergheist", "minipwat", "pwatsune"], */
/*   partOfSpeech: PartOfSpeech::Noun, */
/*   definition: 'some of my plushies', */
/* ); */
/**/
/* function test(Synset $synset) */
/* { */
/*   foreach ($synset->words as $word) { */
/*     echo $word . PHP_EOL; */
/*   } */
/**/
/*   echo $synset->definition . PHP_EOL; */
/* } */
/**/

function findInDataFile(array $offsets)
{
  $dataFile = __DIR__ . '/dict/data.noun';

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
          $wordsArr[] = $line;
        }
      }
    }

    fclose($fp);
    print_r($wordsArr);
  } catch (Exception $e) {
    echo "An error occured: " . $e->getMessage();
  }
}

function readIndexFile(string $inputWord)
{
  $indexFile = __DIR__ . '/dict/index.noun';
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
        findInDataFile($offsets);
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
