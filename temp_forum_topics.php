<?php
try {
    $forums = $conn->query("SELECT * from temp_forum_topics");
    while ($row = $forums->fetch_assoc()) :
?>
        <div class="alert alert-warning alert-dismissible fade show mw-100" role="alert">
            <?php echo $row['title']; ?>
            <button type="button" class="close notif_close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
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