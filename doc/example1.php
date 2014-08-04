<?php
require_once "mersenne_twister.php";

use mersenne_twister\twister;

#--------------------------------------------

$twister1 = new twister(42);
$twister2 = new twister(42);
/*
  42 is a seed for initialising
  the random-number generator.
*/

for($i = 0; $i < 10; $i++)
  # int32 returns a random 32-bit integer
  if($twister1->int32() !== $twister2->int32())
    print "They're different -- " .
      "this is not supposed to happen!\n";

#--------------------------------------------

$num_iters = 1000;

$twister3 = new twister(42);
$saved = serialize($twister3);

$sum = 0;
for($i = 0; $i < $num_iters; $i++)
  $sum += $twister3->rangereal_halfopen(10, 20);
  /*
    the call to rangereal_halfopen produces a
    floating-point number >= 10 and < 20
  */

print "This is the average, " .
  "which should be about 15: " .
  ($sum / $num_iters) . "\n";

$twister3 = unserialize($saved);

# run the loop again
#
$sum = 0;
for($i = 0; $i < $num_iters; $i++)
  $sum += $twister3->rangereal_halfopen(10, 20);

print "This is the average again, " .
  "which should be the same as before: " .
  ($sum / $num_iters) . "\n";

#--------------------------------------------

$twister4 = new twister;

$twister4->init_with_file("/dev/urandom", twister::N);
/*
  This reads characters from /dev/urandom and
  uses them to initialise the random number
  generator.

  The second argument is multiplied by 4 and
  then used as an upper bound on the number of
  characters to read.
*/

if($twister4->rangeint(1, 6) == 6)
  print "You've won -- congratulations!\n";
