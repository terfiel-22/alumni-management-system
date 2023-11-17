<?php include 'db_connect.php' ?>
<?php
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM funds where id=" . $_GET['id'])->fetch_array();
    foreach ($qry as $k => $v) {
        $$k = $v;
    }
}
include "../utils/format_date.php";
?>
<div class="container-fluid">
    <form action="" id="manage-fund">
        <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>" class="form-control">
        <div class="row form-group">
            <div class="col-md-12">
                <label class="control-label">Project</label>
                <select name="project_id" id="project" class="form-control">
                    <option hidden>-- Select Project --</option>
                    <?php
                    $projects = $conn->query("SELECT * from projects order by name asc");
                    while ($row = $projects->fetch_assoc()) :
                    ?>
                        <option value="<?php echo $row['id'] ?>" <?php echo isset($_GET['id']) && $project_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-12">
                <label class="control-label">Fund Manager</label>
                <select name="fund_manager_id" id="fund_manager" class="form-control">
                    <option hidden>-- Select Fund Manager --</option>
                    <?php
                    $alumni = $conn->query("SELECT *, Concat(lastname,', ',firstname,' ',middlename) as name from alumnus_bio order by Concat(lastname,', ',firstname,' ',middlename) asc");
                    while ($row = $alumni->fetch_assoc()) :
                    ?>
                        <option value="<?php echo $row['id'] ?>" <?php echo isset($_GET['id']) && $fund_manager_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-12">
                <label class="control-label">Current Amount Raised</label>
                <input type="text" name="current_amount_raised" class="form-control" value="<?php echo isset($current_amount_raised) ? $current_amount_raised : '' ?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-12">
                <label class="control-label">Target Amount</label>
                <input type="text" name="target_amount" class="form-control" value="<?php echo isset($current_amount_raised) ? $current_amount_raised : '' ?>">
            </div>
        </div>
    </form>
</div>

<script>
    $('.text-jqte').jqte();
    $('#manage-fund').submit(function(e) {
        e.preventDefault()
        start_load()
        $.ajax({
            url: 'ajax.php?action=save_fund',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully saved.", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1000)
                }

                if (resp == 2) {
                    alert_toast("Duplicated project is not allowed.", 'danger')
                    setTimeout(function() {
                        location.reload()
                    }, 1000)
                }
            }
        })
    })
</script>