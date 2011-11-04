<?php

/*
 * Creating PortScanner class  that can be used to scan the ports 
 * for an ip address or a range of ip address and for a port or 
 * for some random ports or a range of ports.
 * 
 * Note: Since there is no concept of threads in php the port 
 * scan process take a long time than it should
 * 
 * Issue: Fixes #2 class.portscanner.php should be a class that can 
 * 		  be used to scan the ports
 */
 
 class PortScanner
{
	private $ip_addresses;  // Array of ip addresses: It can be a 
						    // single ip address or some comma 
						    // seperated ip addresses or a range of ip 
						    // addresses seperated by '-' or mix 
						    // of both.
	private $ports;         // Array of ports: It can be a single 
						    // port or some comma seperated ports 
						    // or a range of ports seperated by '-'
						    // or mix of both.
	private $timeout;	    // The connection timeout, in seconds.
	private $wait;		    // Time to wait between scanning 
						    // individual ports, in microseconds
		
////////////////////////////////////////////////////////////////////////
	
	/*
	 * Class construct to define the ip addresses passed as a string
	 * seperated by comma or '-' or both.
	 */
	public function __construct($ip_string)
	{
		$this->ip_addresses = $this->seperatestr($ip_string, "IP");
		$this->timeout = 2;
		$this->wait = 0;
	}
		
////////////////////////////////////////////////////////////////////////

	/*
	 * Function: setports()
	 * 
	 * Argument: string of ports, can be comma seperated or range 
	 * seperated by '-' or both.
	 * 
	 * The ports to be scanned on each ip address. 
	 * 
	 */
	 
	public function setports($port_str)
	{
		$this->ports = $this->seperatestr($port_str, "PORT");
	}

////////////////////////////////////////////////////////////////////////

	/*
	 * Function: settimeout()
	 * 
	 * Argument: timout value in seconds
	 * 
	 * Time in seconds to wait for response from the machine
	 * 
	 */
	 
	public function settimeout($time)
	{
		$this->timeout = $time;
	}

////////////////////////////////////////////////////////////////////////

	/*
	 * Function: setwait()
	 * 
	 * Argument: wait value in seconds and microseconds
	 * 
	 * Time in microseconds to wait for after each scan of a port
	 * 
	 */
	 
	public function setwait($sec, $microsec)
	{
		$this->wait = (1000000 * $sec) + $microsec;
	}
	

////////////////////////////////////////////////////////////////////////

	/*
	 * Function: scan_output()
	 * 
	 * Argument: Boolean to display or return output in array
	 * 
	 * Function will call scan() to display the output or to return 
	 * output array
	 */
	
	public function scan_output($display)
	{
		$ret = $this->scan();
		if($display == false)
		{
			return $ret;
		}
		else
		{
			$this->display($ret);
		}
	}

////////////////////////////////////////////////////////////////////////

	/*
	 * Function: display()
	 * 
	 * Argument: scan array
	 * 
	 * To display the result in a tabular format
	 * 
	 */
	 
	private function display($res_arr)
	{
		echo "<h4>Port Scan Result</h4>";
		
		foreach($res_arr as $ip=>$ip_results)
		{
			echo $ip . "\n<blockquote>\n";
			
			foreach($ip_results as $port=>$prt_result)
			{
				echo "\t" . $port . " : " . $prt_result['pname'] . " : ";
				
				echo $prt_result['status'] . "<br />\n";
			}
			
			echo "</blockquote>\n\n";
		}
	}
	 

////////////////////////////////////////////////////////////////////////

	/*
	 * Function: scan()
	 * 
	 * Scan and store the result in an array of specific format
	 * 
	 */
	 
	private function scan()
	{
		foreach($this->ip_addresses as $ip_address)
		{
			foreach($this->ports as $port)
			{
				$prt_name = getservbyport($port, "tcp");
				if($prt_name === FALSE)
				{
					$prt_name = "N/A";
				}
				$ip_add = $this->checkandcleanip($ip_address);
				if($ip_add != "invalid")
				{
					$sock = fsockopen($ip_add, $port, 
									  $errno, $errstr, $this->timeout);
				
					if($sock)
					{
						$status = "open";
					}
					else
					{
						$status = $errstr;
					}
				
					$results["$ip_address"]["$port"]["pname"] = "$prt_name";
					$results["$ip_address"]["$port"]["status"] = "$status";
				}
				else
				{
					$results["$ip_address"]["$port"]["pname"] = "$prt_name";
					$results["$ip_address"]["$port"]["status"] = "$ip_add";
				}
			}
			
			//$this->waitfor($this->wait);
			
		}
		
		return $results;
		
	}

////////////////////////////////////////////////////////////////////////

	/*
	 * Function: checkandcleanip()
	 * 
	 * Argument: ip address to be check and cleaned
	 * 
	 * To check if the ip address is a hostname 
	 * if hostname: convert to ip address
	 * 
	 */
	
	private function checkandcleanip($ip_add)
	{
		if(preg_match("\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b", $ip_add))
		{
			return $ip_add;
		}
		else
		{
			$ip = gethostbyname($ip_add);
			if(ip2long($ip) == -1 || 
			   ($ip == gethostbyaddr($ip) //&& 
			   /*preg_match("/.*\.[a-zA-Z]{2,3}$/",$ip_add) == 0*/))
			{
				return "invalid";
			}
			else
			{
				return $ip;
			}
		}
	}

////////////////////////////////////////////////////////////////////////
	
	/*
	 * Function: seperatestr()
	 * 
	 * Argument: string to be seperated and type of data
	 * 
	 * To seperate the string and create an array 
	 * of ip addresses or port numbers
	 * 
	 */
	 
	private function seperatestr($string, $type)
	{
		$sep_arr = explode(",", $string);
		
		switch($type)
		{
			case "IP":
			
			if(count($sep_arr) == 1)
			{
				return $sep_arr;
				break;
			}
			else
			{
				$ip_arr = array();
				foreach($sep_arr as $ip)
				{
					if(strpos($ip, '-') !== false)
					{
						if(preg_match("\D+", $ip))
						{
							array_push($ip_arr, $ip);
						}
						else
						{
							$range_arr = explode("-", $ip);
							
							$start  = ip2long($range_arr[0]);
							$end = ip2long($range_arr[1]);
						
							for($i = $start; $i <= $end; $i++)
							{
								array_push($ip_arr, long2ip($i));
							}
						}
					}
					else
					{
						array_push($ip_arr, $ip);
					}
				}
				
				return $ip_arr;
				break;
			}
						
			case "PORT":
				
			if(count($sep_arr) == 1)
			{
				return $sep_arr;
				break;
			}
			else
			{
				$prt_arr = array();
				foreach($sep_arr as $prt)
				{
					if(strpos($prt, "-") !== false)
					{
						$range_arr = explode("-", $prt);
						
						$start  = $range_arr[0];
						$end = $range_arr[1];
						
						for($i = $start; $i <= $end; $i++)
						{
							array_push($prt_arr, $i);
						}
					}
					else
					{
						array_push($prt_arr, $prt);
					}
				}
				
				return $prt_arr;
				break;
			}
						/*
			default:
			
			echo "<h5>Cannot accept $type as an option.</h5>";
			exit;*/
		}	
	}
		
////////////////////////////////////////////////////////////////////////
}
/*
$address = "localhost";

for($i = 60; $i < 700; $i++) 
{
	$checkport = fsockopen($address, $i, $errnum, $errstr, 2);
	
	if(!$checkport)
	{
		//echo "The port ".$i." from ".$address." seems to be closed.<br />";
	}
	else
	{
		//print_r($checkport);
		echo "The port ".$i." from ".$address." seems to be open.<br />";
	}
}
*/

$ips = "192.168.2.0-192.168.2.5,127.0.1.1";
$prts = "80,3306,30-32";

$portscanner = new PortScanner($ips);
$portscanner->setports($prts);

//$result = 
$portscanner->scan_output(true);

//print_r($result);

?>
