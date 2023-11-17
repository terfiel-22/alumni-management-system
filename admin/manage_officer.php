<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM officers where id=".$_GET['id'])->fetch_array();
	foreach($qry as $k =>$v){
		$$k = $v;
	}
}

?>
<div class="container-fluid">
	<form action="" id="manage-officer">
		<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id']:'' ?>" class="form-control">
		<div class="row form-group">
			<div class="col-md-8">
				<label class="control-label">Alumni</label>
                <select name="alumni" id="alumni" class="form-control" >
                    <option hidden>-- Select Alumni --</option>
                    <?php 
                    $alumni = $conn->query("SELECT *, Concat(lastname,', ',firstname,' ',middlename) as name from alumnus_bio order by Concat(lastname,', ',firstname,' ',middlename) asc");
					while($row=$alumni->fetch_assoc()):
                    ?>
                    <option value="<?php echo $row['id'] ?>" <?php echo isset($_GET['id']) && $alumnus_bio_id == $row['id'] ? 'selected':'' ?>><?php echo $row['name']?></option>
                    <?php endwhile; ?>
                </select> 
			</div>
		</div> 
		<div class="row form-group">
			<div class="col-md-8">
				<label class="control-label">Position</label>
                <select name="position" class="form-control">
                    <option hidden>-- Select Position --</option>
                    <option value="President" <?php echo isset($_GET['id']) && $position == "President" ? 'selected':'' ?>>President</option>
                    <option value="Vice President" <?php echo isset($_GET['id']) && $position == "Vice President" ? 'selected':'' ?>>Vice President</option>
                    <option value="Secretary" <?php echo isset($_GET['id']) && $position == "Secretary" ? 'selected':'' ?>>Secretary</option>
                    <option value="Treasurer" <?php echo isset($_GET['id']) && $position == "Treasurer" ? 'selected':'' ?>>Treasurer</option>
                    <option value="Auditor" <?php echo isset($_GET['id']) && $position == "Auditor" ? 'selected':'' ?>>Auditor</option>
                    <option value="Public Information Manager" <?php echo isset($_GET['id']) && $position == "Public Information Manager" ? 'selected':'' ?>>Public Information Manager</option>
                    <option value="Business Manager" <?php echo isset($_GET['id']) && $position == "Business Manager" ? 'selected':'' ?>>Business Manager</option>
                    <!-- ADD MORE -->
                </select> 
			</div>
		</div>
	</form>
</div>

<script> 
	$('.text-jqte').jqte();
	$('#manage-officer').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_officer',
			method:'POST',
			data:$(this).serialize(),
			success:function(resp){
				if(resp == 1){
					alert_toast("Data successfully saved.",'success')
					setTimeout(function(){
						location.reload()
					},1000)
				}
                if(resp == 2){
					alert_toast("Duplicate alumni/position is not allowed.",'danger')
					setTimeout(function(){
						location.reload()
					},1000)
				}
			}
		})
	})
</script>