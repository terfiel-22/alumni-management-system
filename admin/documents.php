<?php include('db_connect.php');?>

<div class="container-fluid">
<style>
	input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(1.5); /* IE */
  -moz-transform: scale(1.5); /* FF */
  -webkit-transform: scale(1.5); /* Safari and Chrome */
  -o-transform: scale(1.5); /* Opera */
  transform: scale(1.5);
  padding: 10px;
}
</style>
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
						<b>Documents</b>
						<span class="">

							<button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="new_document">
					<i class="fa fa-plus"></i> New</button>
				</span>
					</div>
					<div class="card-body">
						
						<table class="table table-bordered table-condensed table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Name</th>
									<th class="">Creation Date</th>
									<th class="">Uploaded By</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$jobs =  $conn->query("SELECT d.*,u.name as user_name from documents d inner join users u on u.id = d.user_id order by id desc");
                                
                                include '../utils/format_date.php';
								while($row=$jobs->fetch_assoc()):
									
								?>
								<tr>
									
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										 <p><b><?php echo ucwords($row['name']) ?></b></p>
										 
									</td>
									<td class="">
										 <p><b>
                                            <?php  format_date($row['date_created']); ?>
                                        </b></p>
										 
									</td>
									<td class="">
										 <p><b><?php echo ucwords($row['user_name']) ?></b></p>
										 
									</td>
									<td class="text-center">
										<center>
											<div class="btn-group">
											<button type="button" class="btn btn-primary">Action</button>
											<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="sr-only">Toggle Dropdown</span>
											</button>
											<div class="dropdown-menu">
												<a class="dropdown-item" href="assets/uploads/documents/<?php echo $row['id'] ?>_document.<?php echo $row['file_extension'] ?>"
												target="_blank" >Download</a>
												<div class="dropdown-divider"></div>
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
		max-width: 100px;
		max-height: 150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	$('#new_document').click(function(){
		uni_modal("New Entry","manage_document.php",'mid-large')
	})
	
	$('.edit_document').click(function(){
		uni_modal("Manage Document","manage_document.php?id="+$(this).attr('data-id'),'mid-large')
		
	}) 
	$('.delete_document').click(function(){
		_conf("Are you sure to delete this document?","delete_document",[$(this).attr('data-id')],'mid-large')
	})
	function delete_document($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_document',
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