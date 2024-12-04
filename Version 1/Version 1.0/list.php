<?php 
include "./class/.htUserClass.php";
include "./class/.htAdminClass.php";

$user=new UserClass();
$cookie=$user->getAndCheckCookie($_COOKIE['login']);
if (!$cookie) {
	header('Location: ./login.php');
}
else {
	$user->_setUsernameAndPasswort($cookie[0], $cookie[1], true);
}
$admin=new AdminClass();
$files=$admin->listFiles($_GET['type']);
$fileJson=json_encode($files);

if ($_GET['type']=="") {
	header('Location: ./list.php?type=text');
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="styles.min.css">

	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

	<script type="text/javascript">
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
	</script>
	<!-- Vue.js -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script defer>document.addEventListener("touchstart", function(){}, true);</script>
	<title>Alle Dateien</title>
    <!-- Our Custom CSS -->
</head>

<body>

	<!-- NAVBAR --->
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	  <div class="container-fluid">
	    <a class="navbar-brand" href="index.php">Startseite</a>
	    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
	      <span class="navbar-toggler-icon"></span>
	    </button>
	    <div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav">
				<li class="nav-item">
	    			<a class="navbar-brand" href="form.php">Hochladen</a>
				</li>					
				<li class="nav-item">
	    			<a class="navbar-brand" href="list.php">Alle Dateien</a>
				</li>				
				<li class="nav-item">
	    			<a class="navbar-brand" href="help.php">Hilfe</a>
				</li>
			</ul>			
			<ul class="navbar-nav ms-auto mt-2">
				<li class="nav-item" style="margin-right: 25%;">
				  <button onclick="logout()" class="btn btn-info">Logout</button>
				</li>
			</ul>
	    </div>
	  </div>
	</nav>

<div id="mySidebar" class="sidebar">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
  <a href="?type=text" class="nav-link <?php echo $admin->returnActiveIfTypeAreGet("text", $_GET['type']); ?>" aria-current="page"> <i class="fa fa-home"></i><span class="ms-2">Text</span> </a>
  <a href="?type=audio" class="nav-link <?php echo $admin->returnActiveIfTypeAreGet("audio", $_GET['type']); ?>" aria-current="page"> <i class="fa fa-home"></i><span class="ms-2">Audios</span> </a>
  <a href="?type=image" class="nav-link <?php echo $admin->returnActiveIfTypeAreGet("image", $_GET['type']); ?>" aria-current="page"> <i class="fa fa-home"></i><span class="ms-2">Bilder</span> </a>
  <a href="?type=video" class="nav-link <?php echo $admin->returnActiveIfTypeAreGet("video", $_GET['type']); ?>" aria-current="page"> <i class="fa fa-home"></i><span class="ms-2">Videos</span> </a>
</div>
       

	<!-- VUE -->
<button class="openbtn" onclick="openNav()">Datei Art</button>
	<div id="app" class="container">
	    <table class="table table-bordered" empty-text="asd" empty-filtered-text="asd2">
	        <thead>
	            <tr>
	                <th @click="sortBy='username'; sortAsc = !sortAsc">Name</th>
	                <th @click="sortBy='password'; sortAsc = !sortAsc">Pfad/Link</th>
	            </tr>
	            <tr>
		            <th><input class="form-control" v-model="search.name"> </th>
		            <th><input class="form-control" v-model="search.pfad"> </th>
		        </tr>
	        <tbody>
	            <tr v-for="item in filterByValue">
	                <td>{{ item.name }} <a :href="item.deleteLink" onclick="FensterOeffnen(this.href, true); return false" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
  <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
</svg></a></td>
	                <td>
	                	<a :href="item.showLink" target="_blank">
	                		{{ item.pfad }}
	                	</a>
	                </td>
	            </tr>
	<?php 
if (empty($files)) {
	echo "Keine Dateien vorhanden!";
}
	?>
	        </tbody>
	    </table>

	</div>
	 
	<script>
	    var app = new Vue({
	        el: '#app',
	        data: {
	        	search: {
	                name: '',
	                pfad: '',
	            },
	            sortBy: 'name',
	            sortAsc: false,
	            // Hier wird mit PHP die oben definiert files Variable geholt:
	            files: <? echo $fileJson;?>
	        },
	        computed: {
	            filterByValue: function() {
	                return this.files.filter(function(item) {
	                    for(const key in this.search) {
	                        const query = this.search[key].trim();
	                        if(query.length > 0) {
	                        	// Ist diese Zeichenabfolge nicht in query enthalten: 
	                            if(!item[key].includes(query)) {
	                                return false;
	                            }
	                        }
	                    }
	                    return true;
	                }.bind(this))
	            // Tabelle wird wieder nach Usernamen sortiert
	                .slice().sort(function (a, b) {
	                    return (this.sortAsc ? 1 : -1)*a[this.sortBy].localeCompare(b[this.sortBy])}.bind(this)
	                );
	            }
	        }
	 
	    })
	</script>
</body>
</html>
