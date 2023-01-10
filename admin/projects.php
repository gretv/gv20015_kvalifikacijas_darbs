<?php
include 'partials/header.php';

// fetch projects from database
$all_projects_query = "SELECT * FROM projects";
$all_projects = mysqli_query($connection, $all_projects_query);
?>

<section class="section__extra-margin">
    <div class="container">
        <h1></br> All projects</h1>
    </div>
    <div class="container posts__container section__extra-margin">
        <?php while ($project = mysqli_fetch_assoc($all_projects)) : ?>
            <article class="post other__container">
                <h2><?= $project['title'] ?></h2>
                <p class="post__body">
                    <?= substr($project['description'], 0, 150) ?>
                </p>
                <div class="center ">
                    <a href="<?= ROOT_URL ?>admin/project-bugs.php?id=<?= $project['id'] ?>" class="btn">View Issues</a>
                </div>
            </article>
        <?php endwhile ?>
    </div>
</section>

<?php
include '../partials/footer.php';
?>