<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <style>
        input[type=checkbox] {
            /* Double-sized Checkboxes */
            -ms-transform: scale(1.5);
            /* IE */
            -moz-transform: scale(1.5);
            /* FF */
            -webkit-transform: scale(1.5);
            /* Safari and Chrome */
            -o-transform: scale(1.5);
            /* Opera */
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
                        <b>Forum List</b>
                        <span class="">

                            <button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="new_fund">
                                <i class="fa fa-plus"></i> New</button>
                        </span>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-condensed table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="">Fund Name</th>
                                    <th class="">Goal</th>
                                    <th class="">Fund Manager</th>
                                    <th class="">Start Date</th>
                                    <th class="">End Date</th>
                                    <th class="">Current Amount Raised</th>
                                    <th class="">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                include('../utils/format_date.php');
                                $funds =  $conn->query("SELECT f.*,Concat(a.lastname,', ',a.firstname,' ',a.middlename) as fund_manager from funds f inner join alumnus_bio a on a.id = f.fund_manager_id order by f.id desc");
                                while ($row = $funds->fetch_assoc()) :
                                ?>
                                    <tr>

                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td class="">
                                            <p><b><?php echo $row['name'] ?></b></p>
                                        </td>
                                        <td class="">
                                            <p><b><?php echo $row['goal'] ?></b></p>
                                        </td>
                                        <td class="">
                                            <p><b><?php echo $row['fund_manager'] ?></b></p>
                                        </td>
                                        <td class="">
                                            <p><b><?php echo format_date($row['start_date']); ?></b></p>
                                        </td>
                                        <td class="">
                                            <p><b><?php echo format_date($row['end_date']); ?></b></p>
                                        </td>
                                        <td class="">
                                            <p><b><?php echo "PHP " . $row['current_amount_raised'] ?></b></p>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($row['status'] == 1) : ?>
                                                <span class="badge badge-primary">Active</span>
                                            <?php else : ?>
                                                <span class="badge badge-danger">Suspended</span>
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
                                                        <a class="dropdown-item edit_fund" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'>Edit</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item delete_fund" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'>Delete</a>
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
    td {
        vertical-align: middle !important;
    }

    td p {
        margin: unset
    }

    img {
        max-width: 100px;
        max-height: 150px;
    }
</style>
<script>
    $(document).ready(function() {
        $('table').dataTable()
    })
    $('#new_fund').click(function() {
        uni_modal("New Entry", "manage_fund.php", 'mid-large')
    })

    $('.edit_fund').click(function() {
        uni_modal("Manage Fund", "manage_fund.php?id=" + $(this).attr('data-id'), 'mid-large')
    })
    $('.delete_fund').click(function() {
        _conf("Are you sure to delete this fund?", "delete_fund", [$(this).attr('data-id')], 'mid-large')
    })

    function delete_fund($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_fund',
            method: 'POST',
            data: {
                id: $id
            },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)

                }
            }
        })
    }
</script>