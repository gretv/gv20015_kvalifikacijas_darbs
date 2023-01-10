<?php
require 'config/database.php';

// if submit button is pressed
if (isset($_POST['submit'])) {

    // get form data
    // filter sanitize ---> so we dont get any sql queries/ inputs
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $is_admin = filter_var($_POST['userrole'], FILTER_SANITIZE_NUMBER_INT);

    // check inputs
    if (!$firstname || !$lastname) {
        $_SESSION['edit-user'] = "User was not updated because of invalid input on edit page";
    } else {
        // update user
        $query = "UPDATE users SET firstname='$firstname', lastname='$lastname', is_admin=$is_admin WHERE id=$id LIMIT 1";
        $results = mysqli_query($connection, $query);

        // if there are any problems
        if (mysqli_errno($connection)) {
            $_SESSION['edit-user'] = "Could not update user";
        }
        // if there are no problems
        else {
            $_SESSION['edit-user-success'] = "Successfully updated user $firstname $lastname";
        }
    }
}

header('location: ' . ROOT_URL . 'admin/manage-users.php');
die();
