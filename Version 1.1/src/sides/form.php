<?php include "../layout/header.php"; ?>
	<!-- HOCHLADE-FORMULAR-BEREICH -->
	<section id="upload">
		<div class="container">
			<div class="row mt-3">
				<div class="col-1"></div>
				<div class="col-10">

					<!-- FORMULAR -->
			        <form 
			            method="post" 
			            enctype="multipart/form-data" 
			        >
			            <label 
			                for="formFileSm" 
			                class="form-label"
			            >
			                Bitte lade eine 
			                Datei hoch(reinziehen oder auf Durchsuchen klicken)
			            </label>
			            <input 
			                class="
			                    form-control 
			                    form-control-lg
			                " 
			                name="file" 
			                id="file" 
			                type="file" 
			            />
			            <br>
			            <input 
			                type="submit" 
			                class="btn
			                    btn-primary
			                " 
			                name="submit" 
			                value="Upload" 
			            />
			        </form>

				</div>
				<div class="col-1"></div>
			</div>
			<div class="row">
				<div class="col-2"></div>
				<div class="col-8">		
<?php 
$admin=new AdminClass();
$data=$_POST['submit'];

if (isset($data)) {
	$upload=$admin->loadFileUp($data);
	$type_info=$upload[5];
	if (is_array($upload)): ?>

<div class="alert alert-primary" role="alert">
	<b>Filename: </b><?php echo $upload[0]; ?>
	<br>	
	<b>TMP Name: </b><?php echo $upload[1]; ?>
	<br>	
	<b>Type: </b><?php echo $upload[2]; ?>
	<br>	
	<b>Size: </b><?php echo $upload[3] / 1024; ?>kb
	<br>
	Stored in <?php echo $upload[4]; ?>
	<hr>
	Du kannst es dir <?php
		echo "<a href='list.php?type=".$type_info[0]."'>hier</a>";
	?> ansehen.
</div>


<?php
else: ?>
<div class="alert alert-danger" role="alert">
	<b>Return Code: <?php echo $upload; ?></b>
</div>
<?php
endif;
}
?>
				</div>
				<div class="col-2"></div>
			</div>
		</div>
	</section>
<?php include "../layout/footer.php"; ?>