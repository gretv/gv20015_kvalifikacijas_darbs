<?php
include 'partials/header.php';

// fetch the bug from database, if id is set
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM bugs WHERE id=$id";
    $result = mysqli_query($connection, $query);
    $bug = mysqli_fetch_assoc($result);

    // get reporter from users table using reporter_id
    $reporter_id = $bug['reporter_id'];
    $reporter_query = "SELECT * FROM users WHERE id=$reporter_id";
    $reporter_result = mysqli_query($connection, $reporter_query);
    $reporter = mysqli_fetch_assoc($reporter_result);

    // get project from projects table using project_id of bug
    $project_id = $bug['project_id'];
    $project_query = "SELECT * FROM projects WHERE id=$project_id";
    $project_result = mysqli_query($connection, $project_query);
    $project = mysqli_fetch_assoc($project_result);

    // get status from status table using status_id of bug
    $status_id = $bug['status_id'];
    $status_query = "SELECT title FROM statuss WHERE id=$status_id";
    $status_result = mysqli_query($connection, $status_query);
    $status = mysqli_fetch_assoc($status_result);

    // get priority from priotiy table using priority_id of bug
    $priority_id = $bug['priority_id'];
    $priority_query = "SELECT title FROM priority WHERE id=$priority_id";
    $priority_result = mysqli_query($connection, $priority_query);
    $priority = mysqli_fetch_assoc($priority_result);
} else {
    header('location: ' . ROOT_URL . 'admin/search.php');
    die();
}
?>

<section class="singlepost">
    <!-- display error messages -->
    <?php  // shows if edit bug ticket was successful 
    if (isset($_SESSION['edit-bug-success'])) : ?>
        <div class="alert__message success container">
            <p>
                <?= $_SESSION['edit-bug-success'];
                unset($_SESSION['edit-bug-success']) ?>
            </p>
        </div>

    <?php // shows if edit bug ticket was NOT successful 
    elseif (isset($_SESSION['edit-bug'])) : ?>
        <div class="alert__message error container">
            <p>
                <?= $_SESSION['edit-bug'];
                unset($_SESSION['edit-bug']) ?>
            </p>
        </div>

    <?php // shows if delete bug ticket was successful 
    elseif (isset($_SESSION['delete-bug-success'])) : ?>
        <div class="alert__message success container">
            <p>
                <?= $_SESSION['delete-bug-success'];
                unset($_SESSION['delete-bug-success']) ?>
            </p>
        </div>

    <?php // shows if delete bug ticket was NOT successful 
    elseif (isset($_SESSION['delete-bug'])) : ?>
        <div class="alert__message error container">
            <p>
                <?= $_SESSION['delete-bug'];
                unset($_SESSION['delete-bug']) ?>
            </p>
        </div>
    <?php endif ?>

    <div class="container">
        <a href="<?= ROOT_URL ?>admin/" class="btn sm back">Back</a>
    </div>
    </br>
    <div class="container singlepost__container">
        <div>
            <a href="<?= ROOT_URL ?>admin/project-bugs.php?id=<?= $bug['project_id'] ?>" class="category__button"><?= $project['title'] ?></a>
            <div class="status-tag btn-right"><?= $status['title'] ?></div>
            <div class="status-tag btn-right"><?= $priority['title'] ?> priority</div>
        </div>
        <h2 class="post_title"><?= $bug['title'] ?></h2>
        <p style="white-space: pre-line">
            <?= $bug['description'] ?>
        </p>
        <div class="singlepost__thumbnail">
            <img src="../images/<?= $bug['thumbnail'] ?>">
        </div>
        <div class="post__author">
            <div class="post__author-avatar">
                <img src="../images/<?= $reporter['avatar'] ?>">
            </div>
            <div class="post__author-info">
                <h5>By: <?= "{$reporter['firstname']} {$reporter['lastname']}" ?></h5>
                <small>
                    <?= date("M d, Y - H:i", strtotime($bug['date_time'])) ?>
                </small>
            </div>
        </div>
        <div class="section__extra-margin">
            <a href="<?= ROOT_URL ?>admin/csv.php?id=<?= $bug['id'] ?>" class="btn"><i class="dwn"></i>Export as CSV file</a>
            <a href="<?= ROOT_URL ?>admin/delete-bug.php?id=<?= $bug['id'] ?>" class="btn sm danger btn-right">Delete</a>
            <a href="<?= ROOT_URL ?>admin/edit-bug.php?id=<?= $bug['id'] ?>" class="btn sm btn-right">Edit</a>
        </div>
    </div>
</section>

<?php
include '../partials/footer.php';
?>