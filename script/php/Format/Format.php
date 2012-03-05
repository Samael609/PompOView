<?php
	/**
	*
	*/
	abstract class Format {
		protected $matchin;
		protected $replace;
		
		public function __construct($segment) {
			return preg_replace($this->matchin, $this->replace,$segment->text);
		}
	}
?>