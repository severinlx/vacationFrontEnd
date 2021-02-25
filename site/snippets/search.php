<?php
  print "before dirname"
  $path    = dirname(__DIR__, 2) . '/efs';
  print $path
  print array_diff(scandir($path), array('.', '..'));
  /**
  $myPDO = new PDO('sqlite:' . dirname(__DIR__, 2) . '/efs/Vakanzengrabber.db');
	$suchbegriff = ""; //$_GET['Suchbegriff'];
	 if (strcmp($suchbegriff, "") == 0)
	 {
		$query = "Select * From freelancede_2020_10_30 UNION Select * From freelancermap_2020_10_30";
	 }
	 else
	 {
		$query = "Select * From freelancede_2020_10_30 Where Titel like %". $suchbegriff ."% UNION Select * From freelancermap_2020_10_30 Where Titel like %". $suchbegriff ."%";
	 }
     $results = $myPDO->query($query);
	 /**
	 Wenn das Resultat nicht leer ist und ein array ist, wird die ausgabe vorgenommen
	 **
	 if (is_array($results) || is_object($results))
	{
		print "<table>\n";
		print "<thead><tr><th>ID</th><th>Titel</th><th>Start</th><th>Dauer</th><th>Ende</th></tr>\n</thead><tbody>";
		 foreach($results as $result)
		 {
			 print "<tr><td>" . $result[0] . "<td>";
			 print "<td><a href=\"\">" . $result[2] . "</a><td>";
			 print "<td>" . $result[3] . "</td>";
			 print "<td>" . $result[4] . "</td>";
			 print "<td>" . $result[5] . "</td></tr>";
			 //print "<br>";
		 }
	print "</tbody></table>";
	}
	else
	{
		print "Keine Resultate";
	}
	**/
?>