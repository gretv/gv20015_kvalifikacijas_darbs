<?php
include 'partials/header.php';

// check if user is admin
if (isset($_SESSION['user_is_admin']) && ($_SESSION['user_is_admin'] == true)) {
    // get back form data, if there was invalid input
    $title = $_SESSION['add-project-data']['title'] ?? null;
    $description = $_SESSION['add-project-data']['description'] ?? null;

    // delete form data session
    unset($_SESSION['add-project-data']);
} else {
    header('location: ' . ROOT_URL . 'admin');
    die();
}
?>

<section class="form__section section__extra-margin">
    <div class="container form__section-container other__container">
        <h2>Add Project</h2>

        <!-- display error messages -->
        <?php  // shows if add project was NOT successful 
        if (isset($_SESSION['add-project'])) : ?>
            <div class="alert__message error">
                <p>
                    <?= $_SESSION['add-project'];
                    unset($_SESSION['add-project']); ?>
                </p>
            </div>
        <?php endif ?>

        <form action="<?= ROOT_URL ?>admin/add-project-logic.php" method="POST">
            <input type="text" value="<?= $title ?>" name="title" placeholder="Title">
            <textarea rows="4" value="<?= $description ?>" name="description" placeholder="Description"></textarea>
            <button type="submit" name="submit" class="btn">Create</button>
        </form>
    </div>
</section>

<?php
include '../partials/footer.php';
?>