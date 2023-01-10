<?php
require 'config/database.php';

// make sure submit button is pressed, before getting to this page - otherwise this page is not accessable
if (isset($_POST['submit'])) {

    // get form data
    // filter sanitize ---> so we dont get any sql queries/ inputs
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // check inputs
    if (!$title) {
        $_SESSION['add-project'] = "Please enter title";
    } elseif (!$description) {
        $_SESSION['add-project'] = "Please enter description";
    }

    // redirect back to project page with form data, if there was invalid input
    if (isset($_SESSION['add-project'])) {

        // pass form data back to add project page
        $_SESSION['add-project-data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-project.php');
        die();
    } else {

        // insert project into database
        $query = "INSERT INTO projects (title, description) VALUES ('$title', '$description')";
        $result = mysqli_query($connection, $query);

        // if there are any problem
        if (mysqli_errno($connection)) {
            $_SESSION['add-project'] = "Could not add project";
            header('location: ' . ROOT_URL . 'admin/add-project.php');
            die();
        }
        // if there are no problems
        else {
            $_SESSION['add-project-success'] = "Successfully added project $title";
            header('location: ' . ROOT_URL . 'admin/manage-projects.php');
            die();
        }
    }
}
