<?php
	/**
	*
	*/
	class Filtre_Nl extends Filtre {
		protected $matchin = "/\r?\n(\s$\r?\n)*/i";
		protected $replace = "/\n";
	}
?>