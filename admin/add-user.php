<?php
include 'partials/header.php';

// check if user is admin
if (isset($_SESSION['user_is_admin']) && ($_SESSION['user_is_admin'] == true)) {
    // get back form data if there was a reg error
    $firstname = $_SESSION['add-user-data']['firstname'] ?? null;
    $lastname = $_SESSION['add-user-data']['lastname'] ?? null;
    $username = $_SESSION['add-user-data']['username'] ?? null;
    $email = $_SESSION['add-user-data']['email'] ?? null;
    $createpassword = $_SESSION['add-user-data']['createpassword'] ?? null;
    $confirmpassword = $_SESSION['add-user-data']['confirmpassword'] ?? null;

    // delete session add-user data 
    unset($_SESSION['add-user-data']);
} else {
    header('location: ' . ROOT_URL . 'admin');
    die();
}
?>

<section class="form__section section__extra-margin">
    <div class="container form__section-container other__container">
        <h2>Add User</h2>

        <!-- display error messages -->
        <?php // shows if add user was NOT successful 
        if (isset($_SESSION['add-user'])) : ?>
            <div class="alert__message error">
                <p>
                    <?= $_SESSION['add-user'];
                    unset($_SESSION['add-user']);
                    ?>
                </p>
            </div>
        <?php endif ?>

        <form action="<?= ROOT_URL ?>admin/add-user-logic.php" enctype="multipart/form-data" method="POST">
            <input type="text" name="firstname" value="<?= $firstname ?>" placeholder="First Name">
            <input type="text" name="lastname" value="<?= $lastname ?>" placeholder="Last Name">
            <input type="text" name="username" value="<?= $username ?>" placeholder="Username">
            <input type="email" name="email" value="<?= $email ?>" placeholder="Email">
            <input type="password" name="createpassword" value="<?= $createpassword ?>" placeholder="Create password">
            <input type="password" name="confirmpassword" value="<?= $confirmpassword ?>" placeholder="Confirm password">
            <select name="userrole">
                <option value="0">Basic User</option>
                <option value="1">Admin</option>
            </select>
            <div class="form__control">
                <label for="avatar">User Avatar</label>
                <input type="file" name="avatar" id="avatar">
            </div>
            <button type="submit" name="submit" class="btn">Create</button>
        </form>
    </div>
</section>

<?php
include '../partials/footer.php';
?>