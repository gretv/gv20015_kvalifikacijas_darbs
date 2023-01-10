<?php
require 'config/database.php';

// make sure submit button is pressed, before getting to this page - otherwise this page is not accessable
if (isset($_POST['submit'])) {

    // get form data
    // filter sanitize ---> so we dont get any sql queries/ inputs
    $reporter_id = $_SESSION['user-id']; // getting the current logged in user
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $project_id = filter_var($_POST['project'], FILTER_SANITIZE_NUMBER_INT);
    $priority_id = filter_var($_POST['priority'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail = $_FILES['thumbnail'];

    // check inputs
    if (!$title) {
        $_SESSION['add-bug'] = "Please enter title";
    } elseif (!$project_id) {
        $_SESSION['add-bug'] = "Please select project";
    } elseif (!$priority_id) {
        $_SESSION['add-bug'] = "Please select priority";
    } elseif (!$description) {
        $_SESSION['add-bug'] = "Please enter description";
    } elseif (!$thumbnail['name']) {
        $_SESSION['add-bug'] = "Please upload photo";
    } else {

        // work on thumbnail
        // rename the image, so there are no duplicates in database
        $time = time(); // make each image name unique using currect timestamp
        $thumbnail_name  = $time . $thumbnail['name']; // appending name with time
        $thumbnail_tmp_name = $thumbnail['tmp_name'];
        $thumbnail_destination_path = '../images/' . $thumbnail_name; // path where to upload the thumbnail

        // make sure the file is an image
        $allowed_files = ['png', 'jpg', 'jpeg'];
        $extention = explode('.', $thumbnail_name); // array with file info
        $extention = end($extention); // get file extention, which is the last value in the array

        // check if file extention is inside the allowed files 
        if (in_array($extention, $allowed_files)) {

            // make sure image is not too large
            if ($thumbnail['size'] < 10000000) {

                //upload thumnbail
                move_uploaded_file($thumbnail_tmp_name, $thumbnail_destination_path);
            } else {
                $_SESSION['add-bug'] = "File size too big. Should be less than 10MB";
            }
        } else {
            $_SESSION['add-bug'] = "File should be png, jpg or jpeg";
        }
    }

    // redirect back to bug page with form data, if there was invalid input
    if (isset($_SESSION['add-bug'])) {

        // pass form data back to add bug page
        $_SESSION['add-bug-data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-bug.php');
        die();
    } else {

        // insert bug into database
        $query = "INSERT INTO bugs (title, description, thumbnail, project_id, reporter_id, priority_id) VALUES ('$title', '$description', '$thumbnail_name', $project_id, $reporter_id, $priority_id)";
        $result = mysqli_query($connection, $query);

        // if there are no problems
        if (!mysqli_errno($connection)) {
            $_SESSION['add-bug-success'] = "Successfully added bug ticket";
            header('location: ' . ROOT_URL . 'admin/');
            die();
        }
        // if there are any problems
        else {
            $_SESSION['add-bug'] = "Could not add bug ticket";
        }
    }
}

header('location: ' . ROOT_URL . 'admin/add-bug.php');
die();
