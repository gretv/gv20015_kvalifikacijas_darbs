<?php
require 'config/database.php';

// delete the project from database, if id is set
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    //update project id of bugs that belong to this category of untriaged category
    $update_query = "UPDATE bugs SET project_id=13 WHERE project_id=$id";
    $update_result = mysqli_query($connection, $update_query);

    if (!mysqli_errno($connection)) {
        // delete the category 
        $query = "DELETE FROM projects WHERE id=$id LIMIT 1";
        $result = mysqli_query($connection, $query);
        $_SESSION['delete-project-success'] = "Successfully deleted project";
    } else {
        $_SESSION['delete-project'] = "Could not delete project";
    }
}
header('location: ' . ROOT_URL . 'admin/manage-projects.php');
die();
