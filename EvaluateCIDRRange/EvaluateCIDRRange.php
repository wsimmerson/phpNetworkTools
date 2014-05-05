<h3>Extract Network Data from a IP Address with a CIDR Mask</h3>
<br>Usage:
<br>Enter a IP Address - Required
<br>Enter CIDR Notation or Subnet Mask - One Required
<p>
<form method="POST" action=<?php echo $PHP_SELF; ?>>
  IP:<input type="text" name="IP" size="15">/<input type="text" name="cidr" size="2">
  Subnet Mask<input type="text" name="mask" size="15">
  <input type="submit" value="submit"> 
</form>

<?php

	function mask2cidr($mask){
		$long = ip2long($mask);
		$base = ip2long('255.255.255.255');
		return 32-log(($long ^ $base)+1,2);
	}
	
	function cidrToRange($cidr) {
		$range = array();
		$cidr = explode('/', $cidr);
		$range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
		$range[1] = long2ip((ip2long($cidr[0])) + pow(2, (32 - (int)$cidr[1])) -1);
		return $range;
	}
	
	function getNetworkSize($cidr) {
		$rtn = pow(2, 32 - $cidr) -2;
		return $rtn . " IPs";
	}
	
	function isValidIPv4Mask($mask)
	{
		if (!$m = ip2long($mask))
			return false;
	
		$m =~ $m;
		return $m && ~$m && !($m&($m+1));
	}
	
	$IPValid = false; $CIDRValid = false;
	
	if (isset($_POST['IP']) && isset($_POST['cidr']) && empty($_POST['mask'])){
		$cidrRange = range(1, 32);
		
		if (filter_var($_POST['IP'], FILTER_VALIDATE_IP)){
			$IPValid = true; 
		}
		else {
			print "Invalid IP Entered!<br>";
		}
		
		if (in_array((int)$_POST['cidr'], $cidrRange)){
			$CIDRValid = true; 
			$tcidr = $_POST['cidr'];
		}
		else {
			print "Invalid CIDR Mask Entered!<br>";
		}
		
	}
	elseif (isset($_POST['IP']) && isValidIPv4Mask($_POST['mask']) && empty($_POST['cidr'])){
		if (filter_var($_POST['IP'], FILTER_VALIDATE_IP)){
			$IPValid = true;
			$CIDRValid = true;
			$tcidr = mask2cidr($_POST['mask']);
			
		}
		else {
			print "Invalid IP Entered!<br>";
		}
	}
	
	if ($IPValid == true && $CIDRValid == true){
			
		$cidr = $_POST['IP'] . "/" . $tcidr;
		
		$range = cidrToRange($cidr);
		
		$gateway = $range[0];
		$broadcast = $range[1];
		
		$newcidr = $gateway . "/" . $tcidr;
		
		$range = cidrToRange($newcidr);
		
		$gateway = $range[0];
		$broadcast = $range[1];
		
		$cidrmask = $tcidr;
		
		$subnetmask = long2ip(-1 << (32 - (int)$cidrmask));
		
		//if first ip ends in 0 gateway is +1
		$tmpip = explode('.', $gateway);
		if ((int)$tmpip[3] == 0){
			$tmpip[3] = "1";
			$gateway = join('.', $tmpip);
		}
	
		print "<table width=\"100%\"><tr class=\"odd\"><td colspan=\"3\">CIDR: $newcidr</td><tr>";
		print "<tr class=\"even\"><td>Gateway: $gateway</td><td>Broadcast: $broadcast</td><td>Subnet Mask: $subnetmask</td></tr>";
		print "<tr class=\"odd\"><td>Network Size: </td><td>" . getNetworkSize((int)$cidrmask) . "</td><td></td></tr>";
		
		print "</table>";
	}
	
	print "<p><h3>What is CIDR?</h3><p>";
	print "CIDR is the new addressing scheme which replaces the traditional Class A, B, C scheme with a generalized network prefix. Instead of being limited to network identifiers of 8, 16, or 24 bits, CIDR uses anywhere from 13 to 27 bits. this allows creation of IP blocks which more closely fit an organizations specific needs.";
	print "<p>CIDR addresses include the standard 32-bit IP address and also information on how many bits are used for the network prefix.  As per my example at the head of this page.";
	print "<p>My thanks to Vanessa, whose blog post <a href=\"http://www.v-nessa.net/2012/08/07/basic-fun-with-ips-in-php\">Basic Fun with IPs in PHP</a> inspired this tool.";
?>