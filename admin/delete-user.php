<?php
require 'config/database.php';

if (isset($_GET['id'])) {
    // fetch user from database
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM users WHERE id=$id";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_assoc($result);

    // make sure we got bavk only one user
    if (mysqli_num_rows($result) == 1) {
        $avatar_name = $user['avatar'];
        $avatar_path = '../images/' . $avatar_name;
        // delete image if it is available
        if ($avatar_path) {
            unlink($avatar_path);
        }
    }

    // fetch all thumbnails of user's bugs and delete them
    $thumbanils_query = "SELECT thumbnail FROM bugs WHERE reporter_id=$id";
    $thumbnails_result = mysqli_query($connection, $thumbanils_query);
    if (mysqli_num_rows($thumbnails_result) > 0) {
        while ($thumbnail = mysqli_fetch_assoc($thumbnails_result)) {
            $thumbnail_path = '../images/' . $thumbnail['thumbnail'];
            // delete thumbnail from images forlder if exist
            if ($thumbnail_path) {
                unlink($thumbnail_path);
            }
        }
    }

    // delete user from database
    $delete_user_query = "DELETE FROM users WHERE id=$id";
    $delete_user_result = mysqli_query($connection, $delete_user_query);
    if (mysqli_errno($connection)) {
        $_SESSION['delete-user'] = "Could not delete user {$user['firstname']} {$user['lastname']}";
    } else {
        $_SESSION['delete-user-success'] = "Successfully deleted user {$user['firstname']} {$user['lastname']}";
    }
}

header('location: ' . ROOT_URL . 'admin/manage-users.php');
die();
