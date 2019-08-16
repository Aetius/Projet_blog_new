<?php 

class articles{
	
	connection bdd

	public function read(){
		$articles = $pdo->query('SELECT * FROM tableADefinir ORDER BY id DESC LIMIT 10');
		return $articles;
	}
}