<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class WordSmithTest extends TestCase
{
  public static function setUpBeforeClass(): void
  {
    require_once '../bin/wordsmith';
  }

  #[TestDox('Basic')]
  public function testBasicTitleCase(): void
  {
    $this->assertEquals('PNG', acronym('Portable Network Graphics'));
  }
}
