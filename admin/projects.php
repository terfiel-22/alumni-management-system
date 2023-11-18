<?php include('db_connect.php'); ?>

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
                        <b>Projects</b>
                        <span class="">
                            <button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="new_project">
                                <i class="fa fa-plus"></i> New</button>
                        </span>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-condensed table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="">Project Name</th>
                                    <th class="">Goal</th>
                                    <th class="">Start Date</th>
                                    <th class="">End Date</th>
                                    <th class="">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                include('../utils/format_date.php');
                                $funds =  $conn->query("SELECT * from projects order by id desc");
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
                                            <p><b><?php echo format_date($row['start_date'], 'F d, Y'); ?></b></p>
                                        </td>
                                        <td class="">
                                            <p><b><?php echo format_date($row['end_date'], 'F d, Y'); ?></b></p>
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
                                                        <a class="dropdown-item edit_project" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'>Edit</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item delete_project" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'>Delete</a>
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
        $('table').dataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'pdf',
                    footer: true,
                    orientation: 'portrait',
                    pageSize: 'LEGAL',
                    title: 'Projects Report',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'excel',
                    footer: true,
                    orientation: 'portrait',
                    pageSize: 'LEGAL',
                    title: 'Projects Report',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'csv',
                    footer: true,
                    orientation: 'portrait',
                    pageSize: 'LEGAL',
                    title: 'Projects Report',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    }
                },
            ],
        });
    })
    $('#new_project').click(function() {
        uni_modal("New Entry", "manage_project.php")
    })

    $('.edit_project').click(function() {
        uni_modal("Manage Project", "manage_project.php?id=" + $(this).attr('data-id'), 'mid-large')
    })
    $('.delete_project').click(function() {
        _conf("Are you sure to delete this project?", "delete_project", [$(this).attr('data-id')], 'mid-large')
    })

    function delete_project($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_project',
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