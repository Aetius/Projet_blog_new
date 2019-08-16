<?php
namespace App\controller;

class postController{
	public function __construct(){
		echo 'test'; 
	}
	public function show($id){
		echo "show marche avec l'id : $id";
	}
}
