<?php
include 'partials/header.php';

// fetch bugs if id is set
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM bugs WHERE project_id = $id ORDER BY date_time DESC";
    $bugs = mysqli_query($connection, $query);
} else {
    header('location: ' . ROOT_URL);
    die();
}
?>

<section class="container section__extra-margin">
    <h1>
        <?php
        // fetch project from projects table using project_id of bug
        $project_id = $id;
        $project_query = "SELECT * FROM projects WHERE id=$id";
        $project_result = mysqli_query($connection, $project_query);
        $project = mysqli_fetch_assoc($project_result);
        echo $project['title'];
        ?>
    </h1>
</section>

<!-- check if there are any bugs -->
<?php if (mysqli_num_rows($bugs) > 0) : ?>
    <section class="dashboard">
        <!-- display error messages -->
        <?php // shows if edit bug ticket was successful 
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
        <?php endif ?>

        <div class="container other__container">
            <main>
                <table>
                    <thead>
                        <th>Title</th>
                        <th>Project</th>
                        <th>Reporter</th>
                        <th>Priority</th>
                        <th>Created</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        <?php while ($bug = mysqli_fetch_assoc($bugs)) : ?>
                            <!-- get info of each bug -->
                            <?php
                            // get project from projects table using project_id
                            $project_id = $bug['project_id'];
                            $project_query = "SELECT title FROM projects WHERE id=$project_id";
                            $project_result = mysqli_query($connection, $project_query);
                            $project = mysqli_fetch_assoc($project_result);

                            // get reporter from users table using reporter_id
                            $reporter_id = $bug['reporter_id'];
                            $reporter_query = "SELECT firstname, lastname FROM users WHERE id=$reporter_id";
                            $reporter_result = mysqli_query($connection, $reporter_query);
                            $reporter = mysqli_fetch_assoc($reporter_result);

                            // get priority from priorities table using project_id
                            $priority_id = $bug['priority_id'];
                            $priority_query = "SELECT title FROM priority WHERE id=$priority_id";
                            $priority_result = mysqli_query($connection, $priority_query);
                            $priority = mysqli_fetch_assoc($priority_result);

                            // get status from statuss table using status_id
                            $status_id = $bug['status_id'];
                            $status_query = "SELECT title FROM statuss WHERE id=$status_id";
                            $status_result = mysqli_query($connection, $status_query);
                            $status = mysqli_fetch_assoc($status_result);
                            ?>
                            <tr>
                                <td> <a href="<?= ROOT_URL ?>admin/bugs.php?id=<?= $bug['id'] ?>"><?= $bug['title'] ?></a></td>
                                <td><?= $project['title'] ?></td>
                                <td><?= "{$reporter['firstname']} {$reporter['lastname']}" ?></td>
                                <td><?= $priority['title'] ?></td>
                                <td> <?= date("d/m/Y", strtotime($bug['date_time'])) ?> </td>
                                <td><?= $status['title'] ?></td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            </main>
        </div>
    </section>

<?php else : ?>
    <div class="container alert__message error lg section__extra-margin">
        <p>No bug tickets found for this project</p>
    </div>
<?php endif ?>

<?php
include '../partials/footer.php';
?>