const data = null;

const xhr = new XMLHttpRequest();
xhr.withCredentials = true;

xhr.addEventListener("readystatechange", function () {
	if (this.readyState === this.DONE) {
		console.log(this.responseText);
	}
});

xhr.open("GET", "https://imdb8.p.rapidapi.com/title/find?q=game%20of%20thr");
xhr.setRequestHeader("X-RapidAPI-Key", "f7dd01b80cmshbb44046477e6a50p178728jsn12e5bc520b2e");
xhr.setRequestHeader("X-RapidAPI-Host", "imdb8.p.rapidapi.com");

xhr.send(data);
