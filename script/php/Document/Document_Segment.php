<?php
	/**
	*
	*/
	class Document_Segment{
		public $ox;
		public $oy;
		public $nx;
		public $ny;

		public function __construct($ox,$oy,$nx, $ny) {
			$this->ox = $ox;
			$this->oy = $oy;
			$this->nx = $nx;
			$this->ny = $ny;
		}

		public function getNewLen() {
			return $this->ny - $this->nx ; 
		}

		public function toString() {
			return sprintf("%i:%i >> %i:%i",$this->ox,$this->oy,$this->nx,$this->ny);
		}

		public function concat(Document_Segment $objet) {
			$this->ox = $this->ox + $objet->ox;
			$this->oy = $this->oy + $objet->oy;
			$this->nx = $this->nx + $objet->nx;
			$this->ny = $this->ny + $objet->ny;
		}
		
		public function decoupe($coupure){		
			echo $coupure; 		
			$new_coupure= floor(($this->oy-$this->ox+1)*$coupure/($this->ny-$this->nx+1));
			echo ','.$new_coupure;
			$segment1 = new Document_Segment(
				$this->ox,
				$this->ox+$coupure-1,
				$this->nx,
				$this->nx+$new_coupure-1);
			$segment2 = new Document_Segment(
				$this->ox +$coupure,
				$this->oy,
				$this->nx +$new_coupure,
				$this->ny);
			return array($segment1,$segment2);			
		}
	}
?>