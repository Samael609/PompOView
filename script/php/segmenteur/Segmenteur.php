<?php
	/**
	*
	*/
	abstract class Segment {
	protected $texte=file_get_contents('../../index.php');

	public function __construct($texte, $offset_list) {
		$res = array();
		$nb_list =sizeof($offset_list)
		$x=0;
		$current =new segment(0,0,0,0);
		while ($x < $nb_list) {
			$test =test($offset_list[$x]);
			while ($test[0])
			{
				$decoupe = prorata(($offset_list[$x]->oy-$offset_list[$x]->ox+1),$test[1],($offset_list[$x]->ny-$offset_list[$x]->nx+1));
				$curent->concat(new Document_Segment(
					$offset_list[$x]->ox,
					$offset_list[$x]->oy-$offset_list[$x]->oy-$len-1,
					$offset_list[$x]->nx,
					$offset_list[$x]->ny-$decoupe[0]-1)			
				$res[]=$current;		
				$current =new segment(0,0,0,0);
				$offset_list[$x]->ox = $offset_list[$x]->ox+$offset_list[$x]->oy-$len;
				$offset_list[$x]->nx =$offset_list[$x]->nx$decoupe[0]
				$test =test($offset_list[$x]);
			
			}
			$current->concat($offset_list[$x]);
			x++;
		}
		return $res;
	}

	public function getTexte() {
		return $this->texte;
	}
}
?>