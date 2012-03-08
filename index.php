
<pre>
<?php
	require_once "config/config.php";
	$list =array('nl','t');
	$text = file_get_contents('index.php');
	//$text ="abbba     ijdeshgfkler ajzfi++++++jezljfkfjklezhfkh  dsf ezfgezgz";
	$offset = array (new Document_Segment(0,(mb_strlen($text)-1),0,(mb_strlen($text)-1)));
	for( $i=0; $i<sizeof($list);$i++){
		if ( $list[$i] == 't') {
			$res = new Filtre_T($text, $offset);
		}
		if ( $list[$i] == 'nl') {
			$res = new Filtre_Nl($text, $offset);
		}
		$text = $res->getTexte();
		$offset = array_reverse($res->getNewOffset(),TRUE);
	}
?>
</pre>