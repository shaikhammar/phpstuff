<?php
  
  include_once "../time.php";

  class TimeTest //extends PHPUnit_Framework_TestCase
    {
      public function testmicrotime_float()
        {
          echo Time::microtime_float();
        }
    }

  print "Test the TimeTest class. <br />";
  $timetest = new TimeTest();
  $timetest->testmicrotime_float();
?>
