<?php
require 'config/database.php';

// make sure submit button is pressed, before getting to this page - otherwise this page is not accessable
if (isset($_POST['submit'])) {

    // get form data
    // filter sanitize ---> so we dont get any sql queries/ inputs
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $createpassword = filter_var($_POST['createpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmpassword = filter_var($_POST['confirmpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $is_admin = filter_var($_POST['userrole'], FILTER_SANITIZE_NUMBER_INT);
    $avatar = $_FILES['avatar'];

    // check inputs
    if (!$firstname) {
        $_SESSION['add-user'] = "Please enter your first name";
    } elseif (!$lastname) {
        $_SESSION['add-user'] = "Please enter your last name";
    } elseif (!$username) {
        $_SESSION['add-user'] = "Please enter your username";
    } elseif (!$email) {
        $_SESSION['add-user'] = "Please enter a valid email";
    } elseif (strlen($createpassword) < 8 || strlen($confirmpassword) < 8) {
        $_SESSION['add-user'] = "Passwords must be at least 8 characters long";
    } elseif (!$avatar['name']) {
        $_SESSION['add-user'] = "Please add an avatar";
    } else {
        if ($createpassword !== $confirmpassword) {
            $_SESSION['add-user'] = "Passwords do not match";
        } else {
            // hash password
            $hashed_password = password_hash($createpassword, PASSWORD_DEFAULT);

            // check if username or email already exists in database
            $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
            $user_check_result = mysqli_query($connection, $user_check_query);

            if (mysqli_num_rows($user_check_result) > 0) {
                $_SESSION['add-user'] = "Username or Email already exists";
            }
            // no other users found
            else {
                // work on avatar
                // rename the image, so there are no duplicates in database
                $time = time(); // make each image name unique using currect timestamp
                $avatar_name = $time . $avatar['name']; // appending name with time
                $avatar_tmp_name = $avatar['tmp_name'];
                $avatar_destination_path = '../images/' . $avatar_name; // path where to upload the thumbnail

                // make sure file is an image
                $allowed_files = ['png', 'jpg', 'jpeg'];
                $extention = explode('.', $avatar_name); // array with file info
                $extention = end($extention); // get file extention, which is the last value in the array

                // check if file extention is inside the allowed files 
                if (in_array($extention, $allowed_files)) {

                    // make sure that image is not too large(5mb+)
                    if ($avatar['size'] < 5000000) {

                        // upload avatar
                        move_uploaded_file($avatar_tmp_name, $avatar_destination_path);
                    } else {
                        $_SESSION['add-user'] = "File size too big. Should be less then 5MB";
                    }
                } else {
                    $_SESSION['add-user'] = "File should be png, jpg or jpeg";
                }
            }
        }
    }

    // redirect back to bug page with form data, if there was invalid input
    if (isset($_SESSION['add-user'])) {

        // pass form data back to add usser page
        $_SESSION['add-user-data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-user.php');
        die();
    } else {

        // insert new user into users table
        $insert_user_query = "INSERT INTO users SET firstname='$firstname', lastname='$lastname', username='$username', email='$email', password='$hashed_password', avatar='$avatar_name', is_admin=$is_admin";
        $insert_user_results = mysqli_query($connection, $insert_user_query);

        if (!mysqli_errno($connection)) {
            // redirect to login page with success message
            $_SESSION['add-user-success'] = "Successfully added new user $firstname $lastname";
            header('location: ' . ROOT_URL . 'admin/manage-users.php');
            die();
        } else {
            $_SESSION['add-user'] = "Could not add user";
        }
    }
} else {
    // if button wasn't clicked, navigate back to add user page
    header('location: ' . ROOT_URL . 'add-user.php');
    die();
}
