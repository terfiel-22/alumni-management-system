<?php
try {
    $forums = $conn->query("SELECT * from temp_forum_topics");
    while ($row = $forums->fetch_assoc()) :
?>
        <div class="row justify-content-end">
            <div class="col-md-4">
                <div class="alert alert-warning alert-dismissible fade show mw-100" role="alert">
                    <span class="text-muted">New post was added:</span></span>
                    <h4><a href="index.php?page=view_forum&id=<?= $row['forum_id'] ?>" class="link-warning link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover text-success"><?php echo $row['title']; ?></a></h4>
                    <button type="button" class="close notif_close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
<?php endwhile;
} catch (Exception $e) {
} ?>

<script>
    $('.notif_close').click(function() {
        delete_notif();
    })

    function delete_notif($id) {
        start_load()
        $.ajax({
            url: 'admin/ajax.php?action=delete_notif',
            method: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    location.reload()
                }
            }
        })
    }
</script>