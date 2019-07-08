<?php
	$syscontact = snmpget("182.255.0.34", "public", ".1.3.6.1.2.1.1.1.0");
	$uptime = snmpget("182.255.0.34", "public", ".1.3.6.1.2.1.1.3.0");
	$totalmem = snmpget("182.255.0.34", "public", ".1.3.6.1.2.1.25.2.3.1.5.65536");
	$usedmem = snmpget("182.255.0.34", "public", ".1.3.6.1.2.1.25.2.3.1.6.65536");
	$ipaddress = snmpwalk("182.255.0.34", "public", ".1.3.6.1.2.1.4.20.1.1");

	//Menghitung panjang array
	#$panjangipaddress = count($ipaddress);

	echo "Name : ".preg_replace("/STRING:/","",$syscontact)."<br>";
	echo "Uptime : ".$uptime."<br>";
	echo "Total Memmory : ".preg_replace("/INTEGER:/","",$totalmem)."<br>";
	echo "Used Memmory : ".preg_replace("/INTEGER:/","",$usedmem)."<br>";

?>