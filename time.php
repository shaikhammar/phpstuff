<?php

  class Time
    {
      public static function microtime_float()
	{
	  list($usec, $sec) = explode(" ", microtime());

	  return ((float)$usec + (float)$sec);
	}
    }


?>
