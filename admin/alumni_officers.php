<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>ALUMNI Stories</b>
						<!-- <span class="float:right"><a class="btn btn-primary btn-block btn-sm col-sm-2 float-right" href="index.php?page=manage_alumni" id="new_alumni">
					<i class="fa fa-plus"></i> New Entry
				</a></span> -->
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<!-- <colgroup>
								<col width="5%">
								<col width="10%">
								<col width="15%">
								<col width="15%">
								<col width="30%">
								<col width="15%">
							</colgroup> -->
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Avatar</th>
									<th class="">Name</th>
									<th class="">Position</th>
									<th class="">Status</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$alumni = $conn->query("SELECT o.position, a.* ,Concat(a.lastname,', ',a.firstname,' ',a.middlename) as name from officers o inner join alumnus_bio a on o.alumnus_bio_id = a.id order by Concat(a.lastname,', ',a.firstname,' ',a.middlename) asc");
								while($row=$alumni->fetch_assoc()):
									
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="text-center">
										<div class="avatar">
										 <img src="assets/uploads/<?php echo $row['avatar'] ?>" class="" alt="">
										</div>
									</td>
									<td class="">
										 <p> <b><?php echo ucwords($row['name']) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo $row['position'] ?></b></p>
									</td>
									<td class="text-center">
										<?php if($row['status'] == 1): ?>
											<span class="badge badge-primary">Verified</span>
										<?php else: ?>
											<span class="badge badge-secondary">Not Verified</span>
										<?php endif; ?>

									</td>
									<td class="text-center">
										<center>
											<div class="btn-group">
											<button type="button" class="btn btn-primary">Action</button>
											<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="sr-only">Toggle Dropdown</span>
											</button>
											<div class="dropdown-menu">
												<a class="dropdown-item edit_document" href="javascript:void(0)" data-id = '<?php echo $row['id'] ?>'>Edit</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item delete_document" href="javascript:void(0)" data-id = '<?php echo $row['id'] ?>'>Delete</a>
											</div>
											</div>
										</center>
									</td> 
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height:150px;
	}
	.avatar {
	    display: flex;
	    border-radius: 100%;
	    width: 100px;
	    height: 100px;
	    align-items: center;
	    justify-content: center;
	    border: 3px solid;
	    padding: 5px;
	}
	.avatar img {
	    max-width: calc(100%);
	    max-height: calc(100%);
	    border-radius: 100%;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	
	$('.view_alumni').click(function(){
		uni_modal("Bio","view_alumni.php?id="+$(this).attr('data-id'),'mid-large')
		
	})
	$('.delete_alumni').click(function(){
		_conf("Are you sure to delete this alumni?","delete_alumni",[$(this).attr('data-id')])
	})
	
	function delete_alumni($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_alumni',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>