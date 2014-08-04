<?php
namespace mersenne_twister;

use Exception;

class TwisterTest extends \PHPUnit_Framework_TestCase {
  public function setUp() {
    $this->twister = new twister(42);
  }

  /** @dataProvider provideMethodsThatTakeBounds */
  public function testEmptyBoundsDetected($method_name) {
    #> Given

    $low_num = 0;
    $high_num = 100;

    $exception = NULL;

    #> When

    try {
      $this->twister->$method_name($high_num, $low_num);
    } catch(Exception $exception) {}

    #> Then

    $this->assertNotNull($exception);
  }

  public function provideMethodsThatTakeBounds() {
    return array(
      array('rangeint'),
      array('rangereal_open'),
      array('rangereal_halfopen'),
      array('rangereal_halfopen2'),
    );
  }

  public function testFailureOnNonExistentInitFile() {
    #> Given

    $twister = new twister;
    $exception = NULL;

    #> When

    try {
      $twister->init_with_file($this->getNonExistentFile());
    } catch(Exception $exception) {}

    #> Then

    $this->assertNotNull($exception);
  }

  /*
    This tests for a bug which was present at one time.
  */
  public function testRangeIntWorksOnWidest64BitRange() {
    if(PHP_INT_SIZE !== 8) {
      $this->markTestSkipped('not a 64-bit system');
    }

    #> Given

    $twister = new twister(42);
    /*<
      The Mersenne Twister is deterministic, so we can initialise it
      with any value that makes the assertion below true.
    */

    #> When

    $rand = $twister->rangeint(-PHP_INT_MAX - 1, PHP_INT_MAX);

    #> Then

    $this->assertNotEquals($rand, 0);
  }

  private function getNonExistentFile() {
    return __DIR__ . '/non-existent';
  }

  private $twister;
}
