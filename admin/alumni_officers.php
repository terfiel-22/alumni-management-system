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
						<b>ALUMNI Officers</b>
						<span class="">
							<button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="new_officer">
					        <i class="fa fa-plus"></i> New
                            </button>
				        </span>
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
								$alumni = $conn->query("SELECT o.position,o.id as officer_id, a.* ,Concat(a.lastname,', ',a.firstname,' ',a.middlename) as name from officers o inner join alumnus_bio a on o.alumnus_bio_id = a.id order by Concat(a.lastname,', ',a.firstname,' ',a.middlename) asc");
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
                                                <a class="dropdown-item view_alumni" href="javascript:void(0)" data-id = '<?php echo $row['id'] ?>'>View</a>
                                                <div class="dropdown-divider"></div>
												<a class="dropdown-item edit_officer" href="javascript:void(0)" data-id = '<?php echo $row['officer_id'] ?>'>Edit</a> 
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

	$('#new_officer').click(function(){
		uni_modal("New Entry","manage_officer.php",'mid-large')
	})

	$('.view_alumni').click(function(){
		uni_modal("Bio","view_alumni.php?id="+$(this).attr('data-id'),'mid-large')
		
	})
	$('.edit_officer').click(function(){
		uni_modal("Manage Officer","manage_officer.php?id="+$(this).attr('data-id'),'mid-large')
		
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