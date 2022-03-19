<?php 

include "../layout/header.php";

$files=$admin->listFiles($_GET['type']);
$fileJson=json_encode($files);

if ($_GET['type']=="") {
		echo "<script>\nlocation.href='list.php?type=text'\n</script>\n";
}

?>

<div id="mySidebar" class="sidebar">
  <a 
  	href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
  <a href="?type=text" class="nav-link <?php echo $admin->returnActiveIfTypeAreGet("text", $_GET['type']); ?>" aria-current="page">
  	<span class="ms-2">Text</span>
  </a>
  <a href="?type=audio" class="nav-link <?php echo $admin->returnActiveIfTypeAreGet("audio", $_GET['type']); ?>" aria-current="page">
  	<span class="ms-2">Audios</span>
  </a>
  <a href="?type=image" class="nav-link <?php echo $admin->returnActiveIfTypeAreGet("image", $_GET['type']); ?>" aria-current="page">
  	<span class="ms-2">Bilder</span>
  </a>
  <a href="?type=video" class="nav-link <?php echo $admin->returnActiveIfTypeAreGet("video", $_GET['type']); ?>" aria-current="page">
  	<span class="ms-2">Videos</span>
  </a>  
  <a href="?type=all" class="nav-link <?php echo $admin->returnActiveIfTypeAreGet("all", $_GET['type']); ?>" aria-current="page">
  	<span class="ms-2">Alle Dateien</span>
  </a>
</div>
       

<button class="openbtn" onclick="openNav()">Datei Art ändern</button>

	<!-- VUE -->
	<div id="app" class="container">
	    <table class="table table-bordered" empty-text="asd" empty-filtered-text="asd2">
	        <thead>
	            <tr>
	                <th @click="sortBy='username'; sortAsc = !sortAsc">
	                	Name
	                </th>
	                <th @click="sortBy='password'; sortAsc = !sortAsc">
	                	Pfad/Link 
	                	<a href="add.php" target="_blank" style="color: green; font-size: 16px; float: right;">+</a>
	                </th>
	            </tr>
	            <tr>
		            <th>
		            	<input class="form-control" v-model="search.name">
		            </th>
		            <th>
		            	<input class="form-control" v-model="search.pfad">
		            </th>
		        </tr>
	        <tbody>
	            <tr v-for="item in filterByValue">
	                <td>
	                	{{ item.name }} 
	                	<a :href="item.deleteLink" onclick="FensterOeffnen(this.href, true); return false" target="_blank">
	                		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
  											<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
  											<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
											</svg>
										</a>
									</td>
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
	            files: <? echo $fileJson;?>
	        },
	        computed: {
	            filterByValue: function() {
	                return this.files.filter(function(item) {
	                    for(const key in this.search) {
	                        const query = this.search[key].trim();
	                        if(query.length > 0) {
	                            if(!item[key].includes(query)) {
	                                return false;
	                            }
	                        }
	                    }
	                    return true;
	                }.bind(this))
	                .slice().sort(function (a, b) {
	                    return (this.sortAsc ? 1 : -1)*a[this.sortBy].localeCompare(b[this.sortBy])}.bind(this)
	                );
	            }
	        }
	 
	    })
	</script>
	
<?php include "../layout/footer.php"; ?>