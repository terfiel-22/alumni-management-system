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
                        <b>Batch List</b>
                    </div>
                    <div class="card-body">
                        <table class="table table-condensed table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="">Name</th>
                                    <th class="">Course Graduated</th>
                                    <th class="">Batch</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                $alumni = $conn->query("SELECT a.*,c.course,Concat(a.lastname,', ',a.firstname,' ',a.middlename) as name from alumnus_bio a inner join courses c on c.id = a.course_id order by batch asc");
                                while ($row = $alumni->fetch_assoc()) :

                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td class="">
                                            <p> <b><?php echo ucwords($row['name']) ?></b></p>
                                        </td>
                                        <td class="">
                                            <p> <b><?php echo $row['course'] ?></b></p>
                                        </td>
                                        <td><?php echo $row['batch'] ?></td>
                                        <td class="text-center">
                                            <center>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary">Action</button>
                                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item view_alumni" href="javascript:void(0)" data-id='<?php echo $row['id'] ?>'>View</a>
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
    $(document).ready(function() {
        const customize = (doc) => {
            var lastColX = null;
            var lastColY = null;
            var bod = []; // this will become our new body (an array of arrays(lines))
            //Loop over all lines in the table
            doc.content[1].table.body.forEach(function(line, i) {
                //Group based on 4th column (ignore empty cells)
                if (lastColX != line[3].text && line[3].text != '') {
                    //Add line with group header
                    bod.push([{
                        text: line[3].text,
                        style: 'tableHeader'
                    }, '', '', '', '']);
                    //Update last
                    lastColX = line[3].text;
                }
                //Add line with data except grouped data
                if (i < doc.content[1].table.body.length - 1) {
                    bod.push(['', '', {
                            text: line[1].text,
                            style: 'defaultStyle'
                        },
                        {
                            text: line[2].text,
                            style: 'defaultStyle'
                        },
                        {
                            text: line[3].text,
                            style: 'defaultStyle'
                        }
                    ]);
                }
                //Make last line bold, blue and a bit larger
                else {
                    bod.push(['', '', {
                            text: line[1].text,
                            style: 'defaultStyle'
                        },
                        {
                            text: line[2].text,
                            style: 'defaultStyle'
                        },
                        {
                            text: line[3].text,
                            style: 'defaultStyle'
                        }
                    ]);
                }

            });
            //Overwrite the old table body with the new one.
            doc.content[1].table.headerRows = 3;
            doc.content[1].table.widths = [50, 50, 150, 100, 100];
            doc.content[1].table.body = bod;
            doc.content[1].layout = 'lightHorizontalLines';

            doc.styles = {
                subheader: {
                    fontSize: 10,
                    bold: true,
                    color: 'black'
                },
                tableHeader: {
                    bold: true,
                    fontSize: 10.5,
                    color: 'black'
                },
                lastLine: {
                    bold: true,
                    fontSize: 11,
                    color: 'blue'
                },
                defaultStyle: {
                    fontSize: 10,
                    color: 'black'
                }
            }
        };

        $('table').dataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'pdf',
                    footer: true,
                    orientation: 'portrait',
                    pageSize: 'LEGAL',
                    title: 'Batch List Report',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    },
                    customize
                },
                {
                    extend: 'excel',
                    footer: true,
                    orientation: 'portrait',
                    pageSize: 'LEGAL',
                    title: 'Batch List Report',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    },
                    customize
                },
                {
                    extend: 'csv',
                    footer: true,
                    orientation: 'portrait',
                    pageSize: 'LEGAL',
                    title: 'Batch List Report',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    },
                    customize
                },
            ],
            rowGroup: {
                // Group by batch
                dataSrc: 3
            }
        });
    })

    $('.view_alumni').click(function() {
        uni_modal("Bio", "view_alumni.php?id=" + $(this).attr('data-id'), 'mid-large')

    })
</script>