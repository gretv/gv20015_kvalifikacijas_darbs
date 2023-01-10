<?php
include 'partials/header.php';

// fetch projects from database
$query = "SELECT * FROM projects";
$projects = mysqli_query($connection, $query);

// fetch priorities from database
$query_p = "SELECT * FROM priority";
$priorities = mysqli_query($connection, $query_p);

// get back form data if form input was invalid
$title = $_SESSION['add-bug-data']['title'] ?? null;
$description = $_SESSION['add-bug-data']['description'] ?? null;

// delete form data session
unset($_SESSION['add-bug-data']);
?>

<section class="form__section section__extra-margin ">
    <div class="container form__section-container other__container">
        <h2>Create a Ticket</h2>

        <?php // shows error messages 
        if (isset($_SESSION['add-bug'])) : ?>
            <div class="alert__message error">
                <p>
                    <?= $_SESSION['add-bug'];
                    unset($_SESSION['add-bug']);
                    ?>
                </p>
            </div>
        <?php endif ?>

        <form action="<?= ROOT_URL ?>admin/add-bug-logic.php" enctype="multipart/form-data" method="POST">
            <label class="post-label" for="project">Project</label>
            <select name="project">
                <?php
                // display projects in select form
                while ($project = mysqli_fetch_assoc($projects)) : ?>
                    <option value="<?= $project['id'] ?>"><?= $project['title'] ?></option>
                <?php endwhile ?>
            </select>
            <label class="post-label" for="title">Title</label>
            <input type="text" name="title" value="<?= $title ?>">
            <label class="post-label" for="priority">Priority</label>
            <select name="priority">
                <?php
                // display priorities in select form
                while ($priority = mysqli_fetch_assoc($priorities)) : ?>
                    <option value="<?= $priority['id'] ?>"><?= $priority['title']  ?></option>
                <?php endwhile ?>
            </select>
            <label class="post-label" for="description">Description</label>
            <textarea rows="10" name="description"><?= $description ?></textarea>
            <div class="form__control">
                <label for="thumbnail">Add Thumbnail</label>
                <input type="file" name="thumbnail" id="thumbnail">
            </div>
            <button type="submit" name="submit" class="btn">Create</button>
        </form>
    </div>
</section>
<?php
include '../partials/footer.php';
?>