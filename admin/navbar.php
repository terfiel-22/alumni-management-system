<style>
	.collapse a {
		text-indent: 10px;
	}

	nav#sidebar {
		background: url(assets/uploads/<?php echo $_SESSION['system']['cover_img'] ?>) !important
	}
</style>

<nav id="sidebar" class='mx-lt-5 bg-dark'>

	<div class="sidebar-list">
		<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a>
		<a href="index.php?page=gallery" class="nav-item nav-gallery"><span class='icon-field'><i class="fa fa-image"></i></span> Gallery </a>
		<a href="index.php?page=courses" class="nav-item nav-courses"><span class='icon-field'><i class="fa fa-book"></i></span> Academic Strand List</a>
		<a href="index.php?page=alumni" class="nav-item nav-alumni"><span class='icon-field'><i class="fa fa-users"></i></span> Alumni List</a>
		<a href="index.php?page=jobs" class="nav-item nav-jobs"><span class='icon-field'><i class="fa fa-briefcase"></i></span> Career</a>
		<a href="index.php?page=funds" class="nav-item nav-funds"><span class='icon-field'><i class="fa fa-coins"></i></span> Funds </a>
		<a href="index.php?page=forums" class="nav-item nav-forums"><span class='icon-field'><i class="fa fa-comments"></i></span> Program & Events </a>
		<?php if ($_SESSION['login_type'] == 1) : ?>
			<a href="index.php?page=projects" class="nav-item nav-projects"><span class='icon-field'><i class="fa fa-list-ul"></i></span> Projects</a>
			<a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users"></i></span> Users</a>
			<a href="index.php?page=documents" class="nav-item nav-documents"><span class='icon-field'><i class="fa fa-file"></i></span> Documents</a>
			<a href="index.php?page=alumni_officers" class="nav-item nav-alumni_officers"><span class='icon-field'><i class="fa fa-user-tie"></i></span> Alumni Officers</a>
			<a href="index.php?page=batch_list" class="nav-item nav-batch_list"><span class='icon-field'><i class="fa fa-users"></i></span> Batch List </a>
			<a href="index.php?page=site_settings" class="nav-item nav-site_settings"><span class='icon-field'><i class="fa fa-cogs"></i></span> System Settings</a>
		<?php endif; ?>
	</div>

</nav>
<script>
	$('.nav_collapse').click(function() {
		console.log($(this).attr('href'))
		$($(this).attr('href')).collapse()
	})
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>