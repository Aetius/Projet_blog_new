
(function(){
	let submitForm = document.getElementById("submitForm");

	if (submitForm != null) {
		submitForm.addEventListener("click", function(event) {
			if(submitForm !== undefined){
				location.href = "#contact"}});
	}
})();


(function(){

	let articles = document.getElementsByClassName("showArticle");
	let displayTab = function(a){
	let div = a.parentNode.parentNode.parentNode.parentNode;
	let mainDiv = a.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
	/*let parent = a.parentNode;*/
	let comments = document.getElementsByClassName("showComment");

	if (a.classList.contains("active")){
		return false;
	}
	div.querySelector(".active").classList.remove("active");
	a.classList.add("active");

	mainDiv.querySelector(".showOneArticle .active").setAttribute("class", "d-none");
	mainDiv.querySelector(a.getAttribute('href')).setAttribute("class", "active")

	for (let i = 0; i<comments.length; i++){
				let comment = comments[i];
				comment.classList.add('d-none');
	}
	let test = a.getAttribute("id");
	document.getElementById('commentArticle'+test).classList.remove('d-none');
};

	for (let i =0; i<articles.length; i++){
		let article = articles[i];
		article.addEventListener("click", function(event){	
			event.preventDefault(); 
			window.scrollTo(0, 0);
			displayTab(this);
		});
	}
})();

//Go back function
(function(){
	let buttons = document.getElementsByClassName("backButton"); 
	if (buttons != null) {
		for (let i = 0; i<buttons.length; i++){
			let button = buttons[i]; 
			button.addEventListener("click", e => {
				window.history.back();
			})
		}
	}
})();



/*
Close confirmation
*/
(function(){
	let liens = document.getElementsByClassName("confirmClose");
	for(let i=0; i<liens.length; i++){
		let lien = liens[i];
		lien.addEventListener("click", function(event){
			event.stopPropagation(); 
			let rep = window.confirm("Confirmer la suppression ?");
			if (rep === false){
				event.preventDefault(); 
			}
		});

	}

})();


(function(){
	let buttonComment = document.getElementById("visibilityComments");
	buttonComment.addEventListener("click", function(event){
		let comments = document.getElementsByClassName("commentsVisibility");
		
		for (let i = 0; i<comments.length; i++ ){			
			(comments[i].classList.toggle("d-none"));
		}

		if (buttonComment.classList.contains("active")){
			buttonComment.textContent = ("Afficher les commentaires publiés.");
		}else{
			buttonComment.textContent = ("Afficher les commentaires en attente de modération.");
		}
		
	});
})();
	


