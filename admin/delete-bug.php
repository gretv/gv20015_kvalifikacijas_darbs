<?php
require 'config/database.php';

// delete the bug from database, if id is set
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // fetch bug ticket from database in order to delete the thumbnail from the images folder
    $query = "SELECT * FROM bugs WHERE id=$id";
    $result = mysqli_query($connection, $query);

    //make sure only 1 record/bug ticket was fetched
    if (mysqli_num_rows($result) == 1) {
        $bug = mysqli_fetch_assoc($result);
        $thumbnail_name = $bug['thumbnail'];
        $thumbnail_path = '../images/' . $thumbnail_name;

        if ($thumbnail_path) {
            unlink($thumbnail_path);

            // delete bug ticket from database
            $delete_bug_query = "DELETE FROM bugs WHERE id=$id LIMIT 1";
            $delete_bug_result = mysqli_query($connection, $delete_bug_query);

            if (!mysqli_errno($connection)) {
                $_SESSION['delete-bug-success'] = "Successfully deleted bug ticket";
            } else {
                $_SESSION['delete-bug'] = "Could not delete bug ticket";
            }
        }
    }
}

header('location: ' . ROOT_URL . 'admin/');
die();
