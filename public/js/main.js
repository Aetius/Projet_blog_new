
(function(){  /*permet d'autoappeler la fonction, et d'éviter les collision avec d'autres fonctions. */
	let submitForm = document.getElementById("submitForm");


	if (submitForm != null) {
		submitForm.addEventListener("click", function(event) {
		    console.log("click");
		   	if(submitForm != undefined){
		        location.href = "#contact"}});
	}
})();


(function(){

	let articles = document.getElementsByClassName("showArticle");
	
	var displayTab = function(a){
		var div = a.parentNode.parentNode.parentNode.parentNode; 
		var mainDiv = a.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode; 
		var parent = a.parentNode; 
		
		let comments = document.getElementsByClassName('showComment')



			if (a.classList.contains("active")){
				return false;
			}
			div.querySelector('.active').classList.remove('active')
			a.classList.add('active');

			//pour retirer la classe active du para
			//mainDiv.querySelector('.showOneArticle .active').classList.remove('active'); 

			mainDiv.querySelector('.showOneArticle .active').setAttribute('class', 'd-none');
			mainDiv.querySelector(a.getAttribute('href')).setAttribute('class', 'active');
			

			
			for (let i = 0; i<comments.length; i++){
						let comment = comments[i]; 
						comment.classList.add('d-none');
			}
			let test = a.getAttribute('id');
			document.getElementById('commentArticle'+test).classList.remove('d-none');
	}


	for (var i =0; i<articles.length; i++){
		var article = articles[i]; 
		article.addEventListener("click", function(event){	
			event.preventDefault(); 
			window.scrollTo(0, 0);
			displayTab(this);
		})

	}

/*pour sauvegarder l'onglet actif en cas de rechargement (ne fonctionne pas avec le event.preventDefault()
	var hash = window.location.hash; 
	var a = document.querySelector('a[href="'+hash+'"]'); 
	if (a!==null){
		displayTab(a); 
	}
*/	
})();


/*24min pour la vidéo grafikart https://www.grafikart.fr/tutoriels/tp-tabs-776*/

//function goback
(function(){
	let buttons = document.getElementsByClassName("backButton"); 
	if (buttons != null) {
		for (let i = 0; i<buttons.length; i++){
			let button = buttons[i]; 
			button.addEventListener("click", e => {
				window.history.back();
			});
		}
	};
})();



/*
fonction de confirmation de fermeture de page : (à retravailler)
*/
(function(){
	var liens = document.getElementsByClassName('confirmClose'); 
	for(var i=0; i<liens.length; i++){
		var lien = liens[i]; 
		lien.addEventListener('click', function(event){
			event.stopPropagation(); 
			var rep = window.confirm('Confirmer la suppression ?'); 
			if (rep === false){
				event.preventDefault(); 
			}
		});

	}

})();


(function(){
	let buttonComment = document.getElementById('visibilityComments'); 
	buttonComment.addEventListener('click', function(event){
		let comments = document.getElementsByClassName('commentsVisibility'); 
		
		for (let i = 0; i<comments.length; i++ ){			
			(comments[i].classList.toggle('d-none')); 
			console.log(comments[i].classList);
		}

		if (buttonComment.classList.contains("active")){
			buttonComment.textContent = ('Afficher les commentaires publiés.');
		}else{
			buttonComment.textContent = ('Afficher les commentaires en attente de modération.');
		}
		
	});
})();
	

	
/*
	var displayTab = function(a){
		var div = a.parentNode.parentNode.parentNode.parentNode; 
		var mainDiv = a.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode; 
		var parent = a.parentNode; 
		
		let comments = document.getElementsByClassName('showComment')



			if (a.classList.contains("active")){
				return false;
			}
			div.querySelector('.active').classList.remove('active')
			a.classList.add('active');

			//pour retirer la classe active du para
			//mainDiv.querySelector('.showOneArticle .active').classList.remove('active'); 

			mainDiv.querySelector('.showOneArticle .active').setAttribute('class', 'd-none');
			mainDiv.querySelector(a.getAttribute('href')).setAttribute('class', 'active');
			

			
			for (let i = 0; i<comments.length; i++){
						let comment = comments[i]; 
						comment.classList.add('d-none');
			}
			let test = a.getAttribute('id');
			document.getElementById('commentArticle'+test).classList.remove('d-none');
	}
*/

