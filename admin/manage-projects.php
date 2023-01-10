<?php
include 'partials/header.php';

// check if user is admin
if (isset($_SESSION['user_is_admin']) && ($_SESSION['user_is_admin'] == true)) {
    // fetch categories from database
    $query = "SELECT * FROM projects ORDER BY title";
    $projects = mysqli_query($connection, $query);
} else {
    header('location: ' . ROOT_URL . 'admin');
    die();
}

?>
<section class="dashboard">
    <!-- display error messages -->
    <?php // shows if add project was successful
    if (isset($_SESSION['add-project-success'])) : ?>
        <div class="alert__message success container">
            <p>
                <?= $_SESSION['add-project-success'];
                unset($_SESSION['add-project-success']) ?>
            </p>
        </div>

    <?php // shows if add project was NOT successful 
    elseif (isset($_SESSION['add-project'])) : ?>
        <div class="alert__message error container">
            <p>
                <?= $_SESSION['add-project'];
                unset($_SESSION['add-project']) ?>
            </p>
        </div>

    <?php // shows if edit project was NOT successful 
    elseif (isset($_SESSION['edit-project'])) : ?>
        <div class="alert__message error container">
            <p>
                <?= $_SESSION['edit-project'];
                unset($_SESSION['edit-project']) ?>
            </p>
        </div>

    <?php // shows if edit project was successful 
    elseif (isset($_SESSION['edit-project-success'])) : ?>
        <div class="alert__message success container">
            <p>
                <?= $_SESSION['edit-project-success'];
                unset($_SESSION['edit-project-success']) ?>
            </p>
        </div>

    <?php // shows if delete project was successful 
    elseif (isset($_SESSION['delete-project-success'])) : ?>
        <div class="alert__message success container">
            <p>
                <?= $_SESSION['delete-project-success'];
                unset($_SESSION['delete-project-success']) ?>
            </p>
        </div>
    <?php // shows if delete project was NOT successful 
    elseif (isset($_SESSION['delete-project'])) : ?>
        <div class="alert__message error container">
            <p>
                <?= $_SESSION['delete-project'];
                unset($_SESSION['delete-project']) ?>
            </p>
        </div>
    <?php endif ?>

    <div class="container dashboard__container">
        <aside>
            <ul>
                <li>
                    <a href="add-bug.php"><i class="uil uil-pen"></i>
                        <h5>Add Bug Ticket</h5>
                    </a>
                </li>
                <li>
                    <a href="index.php"><i class="uil uil-postcard"></i>
                        <h5>Manage Bug Tickets</h5>
                    </a>
                </li>
                <!-- display full dashboard if user is admin -->
                <?php if (isset($_SESSION['user_is_admin'])) : ?>
                    <li>
                        <a href="add-user.php"><i class="uil uil-user-plus"></i>
                            <h5>Add User</h5>
                        </a>
                    </li>
                    <li>
                        <a href="manage-users.php"><i class="uil uil-users-alt"></i>
                            <h5>Manage Users</h5>
                        </a>
                    </li>
                    <li>
                        <a href="add-project.php"><i class="uil uil-edit"></i>
                            <h5>Add Project</h5>
                        </a>
                    </li>
                    <li>
                        <a href="manage-projects.php" class="active"><i class="uil uil-list-ul"></i>
                            <h5>Manage Projects</h5>
                        </a>
                    </li>
                <?php endif ?>
            </ul>
        </aside>
        <main>
            <h2>Manage Categories</h2>
            <?php if (mysqli_num_rows($projects) > 0) : ?>
                <table>
                    <thead>
                        <th>Title</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </thead>
                    <tbody>
                        <?php while ($project = mysqli_fetch_assoc($projects)) : ?>
                            <tr>
                                <td><?= $project['title']  ?></td>
                                <td><a href="<?= ROOT_URL ?>admin/edit-project.php?id=<?= $project['id'] ?>" class="btn sm">Edit</a></td>
                                <td><a href="<?= ROOT_URL ?>admin/delete-project.php?id=<?= $project['id'] ?>" class="btn sm danger">Delete</a></td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php else : ?>
                <div class="alert__message error"><?= "There are no projects" ?></div>
            <?php endif ?>
        </main>
    </div>
</section>

<?php
include '../partials/footer.php';
?>