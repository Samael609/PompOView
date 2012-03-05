<?php
	/**
	*
	*/
	class Segmenteur_N extends Segmenteur {
		var $nbr = 0;
		var $len = 30;
		
		protected function test($segment){
			if (($nbr+($segment->ny-$segment->nx))< $len) {
				return array (false,null);
			} else {
				$nbr = 0;
				return array(True,$len);
			}
		}
	}
?>