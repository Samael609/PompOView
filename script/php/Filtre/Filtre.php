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
  foreach ($matchs[0] as $values)
{
	$new[] = new Document_Matchs($values[1],
						($values[1]+mb_strlen($values[0])-1),
						null,
						null,
						mb_strlen(preg_replace($this->matchin,$this->replace, $values[0])),						
						mb_strlen($values[0])
	);
}
return $new ;
}

protected function fusion($matchs){
$list_ini = $this->offset_old;
$list_new = array_reverse($matchs,TRUE);
/*print_r($list_ini);
print_r($list_new);*/
$new_list = array();
$compt = 0;
while ( !empty($list_ini) && !empty($matchs)){
	if ( empty($list_ini) && !empty($matchs))
	{
		throw new Exception('erreur liste(s)');
	}
	else if ( empty($list_new) )// test si la liste des nouveaux matchs est vide , on complete la liste final avec la liste initiale
	{
		$t_ini = array_pop($list_ini);
		$new_list [] = new Document_Segment(
		$t_ini->ox,
		$t_ini->oy,
		$compt,
		($compt+($t_ini->oy-$t_ini->ox-1))
		);
		$compt= ($compt +($t_ini->oy-$t_ini->ox));
		echo 'first';
		continue;
	}
	else
	{
	$t_ini = array_pop($list_ini);
	$t_new = array_pop($list_new);
	if ($t_ini->ox >$t_new->ox)
	{
		throw new Exception('erreur match');		
	}
	else if ($t_ini->oy < $t_new->ox) // cas ou le match est strictement supèrieur au segment
	{
		$t_ini->nx = $compt;
		$t_ini->ny =($compt +($t_ini->ny-$t_ini->nx-1));
		$new_list [] = $t_ini;
		$compt= ($compt +($t_ini->ny-$t_ini->nx));
		array_push($list_new, $t_new);
		echo 'scnd ->';
		continue;
	}
	else if($t_ini->ox < $t_new->ox) // cas ou le segment depasse sur le match et commence avant
	{
		$decoupe = $t_ini->decoupe($t_new->ox-$t_ini->ox);
		$decoupe[0]->nx = $compt;
		$decoupe[0]->ny = $compt+$decoupe[0]->ny-$decoupe[0]->nx;
		$new_list [] = $decoupe[0];
		$compt = $decoupe[0]->ny-$decoupe[0]->nx+1;
		array_push($list_ini,$decoupe[1]);
		array_push($list_new,$t_new);
		echo 'thd ->';
		continue;
	}
	else if ($t_ini->oy > $t_new->oy) // cas ou le segment se fini après le match
	{
		$decoupe = $t_ini->decoupe($t_new->oy-$t_ini->ox+1);
		$decoupe[0]->nx = $compt;
		$decoupe[0]->ny = $compt+$t_new->nl-1;
		$new_list [] = $decoupe[0];
		$compt= ($compt + $t_new->nl);
		array_push($list_ini,$decoupe[1]);
		echo 'fourth ->';
		continue;	
	}
	else if ($t_ini->oy < $t_new->oy) // cas ou le segment se fini avant le match
	{
		$decoupe = $t_ini->decoupe(($t_new->oy-$t_new->ox+1),$t_ini->oy-$t_ini->ox+1, $t_new->nl);
		$t_ini->nx = $compt;
		$t_ini->ny =($compt+$decoupe[0]);
		$compt= ($compt + $decoupe[0]);
		$new_list [] = $t_ini;
		$t_new->ox = ($t_ini->oy+1);
		$t_new->nl = $decoupe[2];
		array_push($list_new,$t_new);
		echo 'five ->';
		continue;
	}
	else if ($t_ini->ox == $t_new->ox && $t_ini->oy == $t_new->oy) //si les offset sont égaux
	{
		$t_ini->nx = $compt;
		$t_ini->ny =($compt + $t_new->nl-1);
		$new_list [] = $t_ini;
		$compt = $compt+$t_new->nl;
		echo "one ->";
		continue;
	}
	}
}
$this->new_offset =$new_list;
print_r($this->new_offset);
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