<?php 
function DateTimeForId()
{
	$t = microtime(true);
	$micro = sprintf("%06d",($t - floor($t)) * 1000000);
	$d = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
	return $d->format("YmdHisu"); 	
}
function DateTime()
{
	$t = microtime(true);
	$micro = sprintf("%06d",($t - floor($t)) * 1000000);
	$d = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
	return $d->format("Y-m-d~H:i:s.u"); 	
}
?>