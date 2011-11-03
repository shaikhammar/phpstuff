<?php
include_once "time.php";

echo "<h3> Counting occurrence of a word in a 
	  String :: Benchmarking of PHP functions </h3>";

$array = array();
$time_array = array();

$string = "I have three PHP books, first one is 'PHP Tastes Good', 
next is 'PHP in your breakfast' and the last one is 'PHP Nightmare'";

$word = "PHP";

$arg_array = array($word, $string);

/*
 * Storing Split+Count method into the array
 */

$method = function($word, $string) 
			{
				count(split($word, $string)) - 1;
			};

$array["Split+Count"] = $method;
///////////////////////////////////////////////////////

/*
 * Storing the Preg_Match+Count method into the array
 */
 
$method = function($word, $string)
			{
				preg_match_all("/' . $word . '/i",$str,$matches);
				
				$cnt = count($matches[0]);
			};
			
$array["Preg_Match+Count"] = $method;
///////////////////////////////////////////////////////

/*
 * Storing the str_replace method into the array
 */

$method = function($word, $string)
			{
				str_replace($word, "PP", $string, $cnt);
			};
			
$array["str_replace"] = $method;

///////////////////////////////////////////////////////

/*
 * Storing the str_ireplace method into the array
 */

$method = function($word, $string)
			{
				str_ireplace($word, "PP", $string, $cnt);
			};
			
$array["str_ireplace"] = $method;

///////////////////////////////////////////////////////
 
/*
 * Storing the Explode+Count method into the array
 */

$method = function($word, $string)
			{
				$cnt = count(explode($word, $string))-1;
			};
			
$array["Explode+Count"] = $method;

///////////////////////////////////////////////////////

/*
 * Storing the Array_Functions method into the array
 */

$method = function($word, $string)
			{
				$word_count = (array_count_values(str_word_count(
									strtolower($string),1)));
				
				ksort($word_count);
				
				$cnt = $word_count[$word];
			};
			
$array["Array_Functions"] = $method;

///////////////////////////////////////////////////////

/*
 * Storing the Preg_Split+Count method into the array
 */

$method = function($word, $string)
			{
				$cnt = count(preg_split("/' . $word . '/i",$string))-1;
			};
			
$array["Preg_Split+Count"] = $method;

///////////////////////////////////////////////////////

/*
 * Storing the substr_count method into the array
 */
 
$method = function($word, $string)
			{
				$cnt = substr_count($string, $word);
			};
			
$array["substr_count"] = $method;

///////////////////////////////////////////////////////

echo "<h5>Observations:</h5>";

/*
 * Calling each method to find the time taken to count multiplying
 * it with 10000 to get a value that can be understood easily.
 * 
 * Using function call_user_func_array() which takes the user function 
 * as a closure and arguments as array and execute the function.
 * 
 * Store the times along with method name in $time_array()
 * to enable to find the least and most time taking methods
 * 
 * Note: We are not returning any value as it is used only for 
 * benchmarking process.
 */

foreach($array as $key=>$func)
{
	$start = Time::microtime_float();

	for ($i=0; $i<10000; $i++)
		{
			call_user_func_array($func, $arg_array);
		}
	$end = Time::microtime_float();
	
	echo "Count by $key took : ".($end-$start)." Seconds<BR />";
	
	$time_array["$key"] = $end-$start;
}
///////////////////////////////////////////////////////////////////////

echo "<h5>Results:</h5>";

/*
 * Reverse sort the array to find least time taking method
 */
arsort($time_array);

echo array_pop(array_keys($time_array)) . 
	 " requires the least time.";
////////////////////////////////////////////////////////////

/*
 * Sort the array to find most time taking method
 */
asort($time_array);

echo "<BR />" . array_pop(array_keys($time_array)) . 
	 " requires the most time.";
////////////////////////////////////////////////////////////
?>
