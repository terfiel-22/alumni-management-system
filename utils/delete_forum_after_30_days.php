<?php 

function deleteOldForums($conn) {
    // Calculate the date 30 days ago
    $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));

    // Query to delete posts older than 30 days
    $deleteQuery = "DELETE FROM forum_topics WHERE date_created < '$thirtyDaysAgo'";

    // Execute the query
    $conn->query($deleteQuery);
}