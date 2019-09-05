<?php

echo 'test'; 

$error=[];

function add($x, $y){


if (($x + 1) != 2) {
	throw new Exception("coucou");
	
}
if ($y ==2){
	throw new Exception("hello");

}
}
try {
	echo add($x=3,$y=2 );
	

}
catch(Exception $e){
	array_push($error, ($e->getMessage()));
}

try {
	echo add($x=1, $y=2 );
	

}

catch(Exception $e){
	array_push($error, ($e->getMessage()));
}

var_dump($error);