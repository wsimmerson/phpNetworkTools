<h3>Is your email server Black listed? Verify its status here!</h3><p>
<form method="POST" action=<?php echo $PHP_SELF; ?>>
  <input type="text" name="IP">
  <input type="submit" value="submit"> This may take a moment!
</form>


<?php

if (isset($_POST['IP'])){
	
	$ip = $_POST['IP'];
	
	if (filter_var($ip, FILTER_VALIDATE_IP)){

                print "IP: $ip<p>";
		
		$splode = array_reverse(explode(".", $_POST['IP']));
		$ipr = join(".", $splode); 

		$rblist = array("Spamhaus-ZEN" => "zen.spamhaus.org",
				  		"Spamcop" => "bl.spamcop.net",
				  		"Sorbs" => "dnsbl.sorbs.net",
						"Barracuda" => "b.barracudacentral.org",
						"BlackJak.net" => "rbl.blakjak.net",
						"Dan.me.uk" => "tor.dan.me.uk",
						"IPocalypse" => "dnsbl.ipocalypse.net",
						"EFnet" => "rbl.efnet.org",
						"IPQuery" => "any.dnsl.ipquery.org",
						"JustSpam" => "dnslb.justspam.org",
						"Mailspike-Blacklist" => "bl.mailspike.net",
						"Mailspike-Reputation" => "rep.mailspike.net",
						"McAfee" => "cidr.bl.mcafee.com",
						"Microsoft Forefront" => "dnsbl.forefront.microsoft.com",
						"MIPSpace" => "bl.mipspace.com",
						"nsZones" => "bl.nszones.com",
						"ORBIT" => "rbl.orbitrbl.com",
						"Pedantic-Netblock" => "netblock.pedantic.org",
						"Pedantic-Spam" => "spam.pedantic.org",
						"Polar Communications" => "rbl.polarcomm.net",
						"Proofpoint" => "safe.dnsbl.prs.proofpoint.com",
						"Passive Spam Block List" => "psbl.surriel.com",
						"Rizon" => "dnsbl.rizon.net",
						"Rymsho" => "dnsbl.rymsho.ru",
						"Sender Score" => "bl.score.senderscore.com",
						"Spam Eating Monkey" => "geobl.spameatingmonkey.net",
						"SpamLab" => "rbl.spamlab.com",
						"Web Equipped" => "dnsbl.webequipped.com",
						"ZapBL" => "dnsbl.zapbl.net",
						"Lashback" => "ubl.unsubscore.com",
						"Backscatterer.org" => "ips.backscatterer.org"
				   		);
		
		ksort($rblist);
		
		print "<table class=\"views-table\" width=\"100%\"><tr><td>List</td><td>Status</td><td>Return Code</td></tr>";
		$rown = 0;
		foreach($rblist as $key => $value) {
			$rown++; $listed = false;
			
			if ($rown %2 == 0) {
				$row = "even";
			}
			else {
				$row = "odd";
			}
			
			$target = $ipr . "." . $value;
			
			print "<tr class=$row><td width=\"33%\" >$key</td>";
			print "<td width=\"33%\">";
			
			$response = gethostbyname($target);
			
			if (strcmp($target, $response) != 0){
				print "<img src=\"images/red.png\" alt=\"Black Listed!\" title=\"Black Listed!\">";
				$listed = true;
			}
			else {
				print "<img src =\"images/green.png\" alt=\"All Clear!\" title=\"All Clear!\">";
			}
			print "</td>";
                        print "<td width=\"33%\">";
			if ($listed == true){
				print $response;
			}
                                print "</td></tr>";
             	}
               print "</table>";
	}
}