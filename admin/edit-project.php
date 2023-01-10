<?php
include 'partials/header.php';

// check if user is admin
if (isset($_SESSION['user_is_admin']) && ($_SESSION['user_is_admin'] == true)) {
    if (isset($_GET['id'])) {
        
        // fetch project from database
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $query = "SELECT * FROM projects WHERE id=$id";
        $result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) == 1) {
            $project = mysqli_fetch_assoc($result);
        }
    } else {
        header('location: ' . ROOT_URL . 'admin/manage-projects');
        die();
    }
} else {
    header('location: ' . ROOT_URL . 'admin');
    die();
}
?>

<section class="form__section ">
    <div class="container form__section-container other__container">
        <h2>Edit Project</h2>
        <form action="<?= ROOT_URL ?>admin/edit-project-logic.php" method="POST">
            <input type="hidden" name="id" value="<?= $project['id'] ?>">
            <input type="text" name="title" value="<?= $project['title'] ?>" placeholder="Title">
            <textarea rows="4" name="description" placeholder="Description"><?= $project['description'] ?></textarea>
            <button type="submit" name="submit" class="btn">Update Project</button>
        </form>
    </div>
</section>

<?php
include '../partials/footer.php';
?>