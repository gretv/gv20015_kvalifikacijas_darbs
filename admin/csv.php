<?php
include '../config/database.php';

// fetch bug ticket from database 
$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$query = "SELECT * FROM bugs WHERE id=$id";
$bugs = mysqli_query($connection, $query);



if (mysqli_num_rows($bugs) > 0) {
    $delimiter = ",";
    $filename = "bug-data_" . date('Y-m-d') . ".csv";
    // create a file pointer
    $f = fopen('php://memory', 'w');

    // set column headers
    $fields = array('TITLE', 'DESCRIPTION', 'PRIORITY');
    FPUTCSV($f, $fields, $delimiter);

    //output each row of data, format line as csv and write to file pointer
    while ($bug = mysqli_fetch_assoc($bugs)) {
        $priority_id = $bug['priority_id'];
        $priority_query = "SELECT title FROM priority WHERE id=$priority_id";
        $priority_result = mysqli_query($connection, $priority_query);
        $priority = mysqli_fetch_assoc($priority_result);

        $lineData = array($bug['title'], $bug['description'], $priority['title']);
        fputcsv($f, $lineData, $delimiter);
    }

    // move back to beginning of file
    fseek($f, 0);

    // set headers to download file rather than display it
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    //output all remaining data on a file pointer
    fpassthru($f);
}
exit;
