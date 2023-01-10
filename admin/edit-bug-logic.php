<?php
require 'config/database.php';

// make sure edit bug button was clicked
if (isset($_POST['submit'])) {

    // get form data
    // filter sanitize ---> so we dont get any sql queries/ inputs
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $previous_thumbnail_name = filter_var($_POST['previous_thumbnail_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $project_id = filter_var($_POST['project'], FILTER_SANITIZE_NUMBER_INT);
    $priority_id = filter_var($_POST['priority'], FILTER_SANITIZE_NUMBER_INT);
    $status_id = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail = $_FILES['thumbnail'];

    // check inputs
    if ((!$title) || (!$project_id) || (!$priority_id) || (!$status_id) || (!$description)) {
        $_SESSION['edit-bug'] = "Bug ticket was not updated because of invalid input on edit page";
    } else {
        // delete existing thumbnail if new thumbnail is added
        if ($thumbnail['name']) {
            $previous_thumbnail_path = '../images/' . $previous_thumbnail_name;
            if ($previous_thumbnail_path) {
                unlink($previous_thumbnail_path);
            }

            // work on new thumbnail
            // remname image
            $time = time(); // make each image name upload unique using current timestamp
            $thumbnail_name = $time . $thumbnail['name'];
            $thumbnail_tmp_name = $thumbnail['tmp_name'];
            $thumbnail_destination_path = '../images/' . $thumbnail_name;

            // make sure file in an image
            $allowed_files = ['png', 'jpg', 'jpeg'];
            $extension = explode('.', $thumbnail_name);
            $extension = end($extension);
            if (in_array($extension, $allowed_files)) {

                // make sure avatar is no more than 5mb
                if ($thumbnail['size'] < 5000000) {

                    // upload avatar
                    move_uploaded_file($thumbnail_tmp_name, $thumbnail_destination_path);
                } else {
                    $_SESSION['edit-bug'] = "Bug ticket was not updated because file size is too big. Should be less than 10MB";
                }
            } else {
                $_SESSION['edit-bug'] = "Bug ticket was not updated because file should be png, jpg or jpeg";
            }
        }
    }

    // redirect back to manage bug page, if there was invalid input
    if ($_SESSION['edit-bug']) {
        header('location: ' . ROOT_URL . 'admin/bug.php?id=' . $id);
        die();
    } else {

        // set thumbnail name if a new one was updated, else keep old thumbnail name
        $thumbnail_to_insert = $thumbnail_name ?? $previous_thumbnail_name;

        $query = "UPDATE bugs SET title='$title', description='$description', thumbnail='$thumbnail_to_insert', project_id=$project_id, priority_id=$priority_id, status_id=$status_id WHERE id=$id LIMIT 1";
        $result = mysqli_query($connection, $query);
    }

    // if there are no problems
    if (!mysqli_errno($connection)) {
        $_SESSION['edit-bug-success'] = "Successfully updated bug ticket";
    }
    // if there are any problems
    else {
        $_SESSION['edit-bug'] = "Could not update bug ticket";
    }
}

header('location: ' . ROOT_URL . 'admin/bug.php?id=' . $id);
die();
