<?php
include 'partials/header.php';

// fetch the categories from database
$project_query = "SELECT * FROM projects";
$projects = mysqli_query($connection, $project_query);

// fetch priorities from database
$query_p = "SELECT * FROM priority";
$priorities = mysqli_query($connection, $query_p);

// fetch statuses from database
$query_p = "SELECT * FROM statuss";
$statuses = mysqli_query($connection, $query_p);

// fetch bug data from database if id is set
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM bugs WHERE id=$id";
    $result = mysqli_query($connection, $query);
    $bug = mysqli_fetch_assoc($result);
} else {
    header('location: ' . ROOT_URL . 'admin/');
    die();
}

?>
<section class="form__section section__extra-margin">
    <div class="container form__section-container other__container">
        <h2>Edit Ticket</h2>
        <form action="<?= ROOT_URL ?>admin/edit-bug-logic.php" enctype="multipart/form-data" method="POST">
            <input type="hidden" name="id" value="<?= $bug['id'] ?>">
            <input type="hidden" name="previous_thumbnail_name" value="<?= $bug['thumbnail'] ?>">
            <label class="post-label" for="project">Project</label>
            <select name="project">
                <?php while ($project = mysqli_fetch_assoc($projects)) : ?>
                    <option value="<?= $project['id'] ?>"><?= $project['title'] ?></option>
                <?php endwhile ?>
            </select>
            <label class="post-label" for="status">Status</label>
            <select name="status">
                <?php while ($status = mysqli_fetch_assoc($statuses)) : ?>
                    <option value="<?= $status['id'] ?>"><?= $status['title'] ?></option>
                <?php endwhile ?>
            </select>
            <label class="post-label" for="title">Title</label>
            <input type="text" name="title" value="<?= $bug['title'] ?>" placeholder="Title">
            <label class="post-label" for="priority">Priority</label>
            <select name="priority">
                <?php while ($priority = mysqli_fetch_assoc($priorities)) : ?>
                    <option value="<?= $priority['id'] ?>"><?= $priority['title'] ?></option>
                <?php endwhile ?>
            </select>
            <label class="post-label" for="description">Description</label>
            <textarea rows="10" name="description" placeholder="Description"><?= $bug['description'] ?></textarea>
            <div class="form__control">
                <label for="thumbnail">Update Thumbnail</label>
                <input type="file" name="thumbnail" id="thumbnail">
            </div>
            <button type="submit" name="submit" class="btn">Update Ticket</button>
        </form>
    </div>
</section>
<?php
include '../partials/footer.php';
?>