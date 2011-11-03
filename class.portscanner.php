<?php

/*
 * Designing a vanila port scanner that can go through all
 * ports to check which ports are open and which ones are closed.
 * 
 * Note: Vanila Scan: It is a type of scanning in which only the ports
 * are scanned to check if they are open or closed. Ports are not 
 * triggered externally to changed their state.
 * 
 * scanning local machine for all ports
 */
 
 $address = "localhost";
 
 for($i = 0; $i < 65586; $i++) 
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
	

?>
