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
            <div class="col-md-8">
                <label class="control-label">Fund Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo isset($name) ? $name : '' ?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-8">
                <label class="control-label">Goal</label>
                <input type="text" name="goal" class="form-control" value="<?php echo isset($goal) ? $goal : '' ?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-8">
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
            <div class="col-md-8">
                <label class="control-label">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo isset($start_date) ? format_date($start_date, 'Y-m-d') : '' ?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-8">
                <label class="control-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo isset($end_date) ? format_date($end_date, 'Y-m-d') : '' ?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-8">
                <label class="control-label">Current Amount Raised</label>
                <input type="text" name="current_amount_raised" class="form-control" value="<?php echo isset($current_amount_raised) ? $current_amount_raised : '' ?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-8">
                <label class="control-label">Status</label>
                <select name="status" class="form-control">
                    <option hidden>-- Select Status --</option>
                    <option value="1" <?php echo isset($status) && $status == "1" ? 'selected' : '' ?>>Active</option>
                    <option value="2" <?php echo isset($status) && $status == "2" ? 'selected' : '' ?>>Suspended</option>
                    <!-- ADD MORE -->
                </select>
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
            }
        })
    })
</script>