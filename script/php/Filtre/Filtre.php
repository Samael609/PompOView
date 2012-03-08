<?php
	/**
	*
	*/

	abstract class Filtre {
	private $texte;
	private $offset_old;
	private $offset_new;

	protected $matchin;
	protected $replace;

	public function __construct($texte, $offset_list) {
		$this->texte = $texte;
		$this->offset_old = $offset_list;
		$this->genere();
	}

	protected function genere() {
		$matchs = array();
		preg_match_all($this->matchin,$this->texte,$matchs,PREG_OFFSET_CAPTURE);
		$matchs = $this-> matchsFormat($matchs);
		$this->texte = $this->replace($this->texte);
		$this->fusion($matchs);
	}

	protected function matchsFormat($matchs) {
		$new = array();
		foreach ($matchs[0] as $values){
			$new[] = new Document_Matchs($values[1],
						($values[1]+mb_strlen($values[0])-1),
						mb_strlen($this->replace)
			);
		}
		return $new ;
	}

	protected function fusion($matchs){
		$list_ini = $this->offset_old;
		$list_new = array_reverse($matchs,TRUE);
		$new_list = array();
		$compt = 0;
		while ( !empty($list_ini) && !empty($matchs)){
			if ( empty($list_ini) && !empty($matchs)){
				throw new Exception('erreur liste(s)');
			}
			$t_ini = array_pop($list_ini);
			$t_new = array_pop($list_new);
			if ($t_new == null or $t_ini->oy < $t_new->ox ) {// test si la liste des nouveaux matchs est vide , on complete la liste final avec la liste initiale
				$t_ini->ny =($compt+($t_ini->ny-$t_ini->nx));
				$t_ini->nx =$compt;
				$compt= ($compt +($t_ini->oy-$t_ini->ox));
				$new_list [] = $t_ini;
				if ($t_new != null){
					array_push($list_new,$t_new);
				}
				continue;
			}
			else if ($t_ini->ox > $t_new->ox){
				throw new Exception('erreur match');		
			}
			else if($t_ini->ox < $t_new->ox) {// cas ou le segment depasse sur le match et commence avant
				$decoupe = $t_ini->decoupe($t_new->ox-$t_ini->ox);
				$decoupe[0]->ny = $compt+$decoupe[0]->ny-$decoupe[0]->nx;
				$decoupe[0]->nx = $compt;
				$new_list [] = $decoupe[0];
				$compt = ($decoupe[0]->ny+1);
				array_push($list_ini,$decoupe[1]);
				array_push($list_new,$t_new);
				continue;
			}
			else if ($t_ini->oy > $t_new->oy){ // cas ou le segment se fini après le match
				$decoupe = $t_ini->decoupe($t_new->oy-$t_ini->ox+1);
				$decoupe[0]->nx = $compt;
				if ($t_new->nl==0){
					$decoupe[0]->ny = $compt+$t_new->nl;
				}
				else{
					$decoupe[0]->ny = $compt+$t_new->nl-1;
				}
				$new_list [] = $decoupe[0];
				$compt= ($compt + $t_new->nl);
				array_push($list_ini,$decoupe[1]);
				continue;	
			}
			else if ($t_ini->oy < $t_new->oy) {// cas ou le segment se fini avant le match
				$decoupe = $t_new->decoupe($t_ini->oy - $t_new->ox+1);
				$t_ini->nx = $compt;
				if ($decoupe[0]==0){
					$t_ini->ny =($compt+$decoupe);
				}
				else{
					$t_ini->ny =($compt+$decoupe)-1;
				}
				$compt= ($compt + $decoupe);
				$t_new->ox = $t_ini->oy+1;
				$new_list [] = $t_ini;
				array_push($list_new,$t_new);
				continue;
			}
			else {//si les offset sont égaux
				$t_ini->nx = $compt;
				$t_ini->ny =($compt + $t_new->nl-1);
				$new_list [] = $t_ini;
				$compt = $compt+$t_new->nl;
				continue;
			}
		}
		$this->offset_new =$new_list;
	}
	
	protected function replace($chaine) {
		return preg_replace($this->matchin, $this->replace, $chaine);
	}

	public function getTexte() {
		return $this->texte;
	}

	public function getNewOffset() {
		return $this->offset_new;
	}
}
?>