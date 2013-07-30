<?php
//Create random character sequence
class randchar{
	function rand_char(){
		//Let's generate a totally random string using md5
    	$md5 = md5(rand(0,999)); 
    	//We don't need a 32 character long string so we trim it down to 5 
    	$pass = substr($md5, 10, 5); 
		
    	return $pass;	
	}
	
	public function randnumber(){
		$randnum = rand(999999999999, 999999999999999999999);
		return $randnum;
	}
	
}
?>