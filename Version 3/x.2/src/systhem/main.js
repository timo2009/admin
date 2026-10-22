function setCookie(cname,cvalue,exdays) {
	let d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	let expires = "expires=" + d.toGMTString();
	document.cookie = cname + "=" + cvalue + ";" + expires +
	";path=/;SameSite=Lax";
}
function logout() {
	setCookie('login', false, -1);
	location.reload();
}
function openNav() {
  document.getElementById("mySidebar").style.width = "250px";
  document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
  document.getElementById("mySidebar").style.width = "0";
  document.getElementById("main").style.marginLeft= "0";
}
function FensterOeffnen (Adresse, reload=false) {
	let deleteConfirm=confirm("Möchtest du diese Datei wirklich löschen?");
	if (deleteConfirm) {
	  MeinFenster = window.open(Adresse, "Zweitfenster", "width=400,height=400,left=100,top=200");
	  MeinFenster.focus();
	  if (reload) {
	  	setTimeout(function() {location.reload()}, 500);
	  }
	}

}