const suche = document.getElementById("suche");
var test1 = document.getElementById("test");
var value = suche.value;
var data;

suche.addEventListener(onclick, test());

function test(){
	const xmlhttp = new XMLHttpRequest();

	xmlhttp.onload = function() {
		data = this.responseText;
		console.log("data" + data);
	}
	xmlhttp.open("GET", "index.php"); //q=" + str
	xmlhttp.send();

}
console.log(data);
for(var a =0; a<= data[a].length ; a++){
	test1.innerHTML= data[a];
}

document.addEventListener("DOMContentLoaded", function(){
// make it as accordion for smaller screens
	if (window.innerWidth > 992) {

		document.querySelectorAll('.navbar .nav-item').forEach(function(everyitem){

			everyitem.addEventListener('mouseover', function(e){

				let el_link = this.querySelector('a[data-bs-toggle]');

				if(el_link != null){
					let nextEl = el_link.nextElementSibling;
					el_link.classList.add('show');
					nextEl.classList.add('show');
				}

			});
			everyitem.addEventListener('mouseleave', function(e){
				let el_link = this.querySelector('a[data-bs-toggle]');

				if(el_link != null){
					let nextEl = el_link.nextElementSibling;
					el_link.classList.remove('show');
					nextEl.classList.remove('show');
				}


			})
		});

	}
// end if innerWidth
});
// DOMContentLoaded  end