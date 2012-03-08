<?php

	class Document_Matchs{
		var $ox;
		var $oy;
		var $nl;

		public function __construct($ox,$oy,$nl) {
			$this->ox = $ox;
			$this->oy = $oy;
			$this->nl = $nl;
		}
		
		public function decoupe($coupure){
			if ($this->nl ==0){
				$this->nl = 0;
				return 0;
			}
			else {
				$new_coupure= floor(($this->oy-$this->ox+1)*$coupure/$this->nl);
				$this->nl = $this->nl-$new_coupure;
				return $new_coupure;
			}			
		}
	}
?>