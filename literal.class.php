<?php
/**
 *
 * @author aurelle meless
 * @license GPL license v2
 * @copyright ivoire maker 2013
 */
class literal {

	protected 	$lettre=array("unit"=>array(1 =>'un' ,
			2=>'deux',
			3=>'trois',
			4=>'quatre',
			5=>'cinq',
			6=>'six',
			7=>'sept',
			8=>'huit',
			9=>'neuf',
			10=>'dix',
			11=>'onze',
			12=>'douze',
			13=>'treize',
			14=>'quatorze',
			15=>'quinze',
			16=>'seize'),
			"double"=> array(20=>'vingt',
					30=>'trente',
					40=>'quarante',
					50=>'cinquante',
					60=>'soixante',
					70=>'soixante-dix',
					80=>'quatre-vingt',
					90=>'quatre-vingt-dix'),
			"segment"=>array(100=>'cent',
					1000=>'mille',
					1000000=>'million',
					1000000000=>'milliard')
	);
	protected 	$ban=array(" ", "\t", "\n", "\r", "\0", "\x0B", "\xA0");
	public 		$number=null;//the number
	public 		$million_number=null;// the million segment number
	public 		$billion_number=null;// the billion segment number
	public 		$mil_number=null;// the mil segment number
	public 		$unit_number=null;// the unit segment number : from 1 to 999

	function __constuct(){

	}
	/**
	 *
	 * @param int $segment
	 * @return string
	 */
	function convertUnit($segment){
		$r='';
		if ($segment<17) {
			$r=$this->lettre['unit'][$segment];
		} else if ($segment<20) {
			$r=$this->lettre['unit'][10].'-'.$this->lettre['unit'][$segment[1]];
		}else if ($segment<100) {
			if ($segment[1]==0) {
				if ($segment==80) {
					$r=$this->lettre['double'][$segment].'s ';
				}else{
					$r=$this->lettre['double'][$segment];
				}
			}else {
				$r=$this->lettre['double'][$segment[0].'0'].'-'.$this->lettre['unit'][$segment[1]];
			}
				
		}
		return $r;
	}
	/**
	 *
	 * @param int $segment
	 * @return string
	 */

	function convertSegment($segment){

		$r='';
		if ($segment<100) {
			$r=$this->convertUnit($segment);
		}else if ($segment<200) {
			if ($segment[1]==0 && $segment[2]==0) {

				$r=$this->lettre['segment'][$segment];
			}else {
				$r=$this->lettre['segment'][$segment[0].'00'].' '.$this->convertUnit($segment[1].$segment[2]);
			}
				
		}else if ($segment<1000) {
			if ($segment[1]==0 && $segment[2]==0) {

				$r=$this->lettre['unit'][$segment[0]].' '.$this->lettre['segment'][100].'s';
			}else {
				$r=$this->lettre['unit'][$segment[0]].' '.$this->lettre['segment'][100].'s '.$this->convertUnit($segment[1].$segment[2]);
			}
		}

		return $r;
	}
	/**
	 *
	 * @param int $nombre
	 * @return array
	 */
	function getBillion($nombre){
		$n= array('size'=>null,'number'=>null);
		$tailleNombre=strlen($nombre);
		$tailleBoucle=$tailleNombre-9;
		$n['size']=$tailleBoucle;

		if ($tailleNombre>9) {

			for($i=0;$i<$tailleBoucle;$i++) {
				$n['number'].=$nombre[$i];
			}
		}
		return $n;
	}
	/**
	 *
	 * @param int $nombre
	 * @return array
	 */
	function getSegment($nombre,$start,$pre=0){
		$n= array('size'=>null,'number'=>null);
		$tailleNombre=strlen($nombre);//longueur du nombre
		$tailleBoucle=$tailleNombre-$start;//taille de la boucle
		$n['size']=$tailleBoucle;

		if ($pre<0)
			$pre=0;

		if ($tailleNombre>$start) {
			for($i=$pre;$i<$tailleBoucle;$i++) {
				$n['number'].=$nombre[$i];
			}
		}
		return $n;
	}
	/**
	 *
	 * @param int $number
	 * @return string
	 */
	function literalize($number){
		$number=$this->sinitize($number);
		$this->number=$number;
		$literal='';
		//start treatment
		$this->billion_number=$this->getBillion($number);
		//
		$this->million_number=$this->getSegment($number,6,$this->billion_number['size']);
		$this->mil_number=$this->getSegment($number,3,$this->million_number['size']);
		$this->unit_number=$this->getSegment($number,0,$this->mil_number['size']);

		if ($this->billion_number){
			if ($this->billion_number['number']>1) {
				$literal.=$this->convertSegment($this->billion_number['number']).' milliards ';
			}else if($this->billion_number['number']==1){
				$literal.=$this->convertSegment($this->billion_number['number']).' milliard ';
			}
		}

		if ($this->million_number['number']){
			if ($this->million_number['number']>1) {
				$literal.=$this->convertSegment($this->million_number['number']).' millions ';
			}else if ($this->million_number['number']==1){
				$literal.=$this->convertSegment($this->million_number['number']).' million ';
			}
		}

		if ($this->mil_number['number']){
			if ($this->mil_number['number']>=1) {
				$literal.=$this->convertSegment($this->mil_number['number']).' mille ';
			}
		}

		if ($this->unit_number['number']){
			if ($this->unit_number['number']>=1) {
				$literal.=$this->convertSegment($this->unit_number['number']);
			}
		}
		return $literal;
	}
	
	function sinitize($car){
		$r=str_replace($this->ban, array(), $car);
		return $r;
	}

}
