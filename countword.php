<?php
include_once "time.php";

echo "<h3> Counting occurrence of a word in a String :: Benchmarking of PHP functions </h3><BR /><BR />";


$str = "I have three PHP books, first one is 'PHP Tastes Good', 
next is 'PHP in your breakfast' and the last one is 'PHP Nightmare'";

$start = Time::microtime_float();
for ($i=0; $i<10000; $i++)
{

    $cnt = count(split("PHP",$str))-1;
}
$end = Time::microtime_float();

echo "Count by Split+Count took : ".($end-$start)." Seconds<BR />";

$start = Time::microtime_float();

for ($i=0; $i<10000; $i++)
{
    preg_match_all("/php/i",$str,$matches);

    $cnt = count($matches[0]);

}
$end = Time::microtime_float();
echo "Count by Preg_Match+Count took : ".($end-$start)." Seconds<BR />";

$start = Time::microtime_float();

for ($i=0; $i<10000; $i++)
{

    str_replace("PHP","PP",$str,$cnt);
    //echo $cnt;
}
$end = Time::microtime_float();

echo "Count by str_replace took : ".($end-$start)." Seconds<BR />";

$start = Time::microtime_float();

for ($i=0; $i<10000; $i++)
{
    str_ireplace("PHP","PP",$str,$cnt);

    //echo $cnt;
}
$end = Time::microtime_float();
echo "Count By str_ireplace took : ".($end-$start)." Seconds<BR />";

$start = Time::microtime_float();

for ($i=0; $i<10000; $i++)
{

    $cnt = count(explode("PHP",$str))-1;
    //echo $cnt;
}
$end = Time::microtime_float();

echo "Count By Explode+Count took : ".($end-$start)." Seconds<BR />";

$start = Time::microtime_float();

for ($i=0; $i<10000; $i++)
{
    $word_count = (array_count_values(str_word_count(strtolower($str),1)));

    ksort($word_count);

    $cnt = $word_count['php'];
}
$end = Time::microtime_float();
echo "Count By Array Functions took : ".($end-$start)." Seconds<BR />";

$start = Time::microtime_float();
for ($i=0; $i<10000; $i++)

{
    $cnt = count(preg_split("/PHP/i",$str))-1;
}
$end = Time::microtime_float();

echo "Count By preg_split+Count took : ".($end-$start)." Seconds<BR />";

$start = Time::microtime_float();
for ($i=0; $i<10000; $i++)
{

    $cnt = substr_count($str, "PHP");
}
$end = Time::microtime_float();
echo "Count By substr_count took : ".($end-$start)." Seconds<BR />";

?>
