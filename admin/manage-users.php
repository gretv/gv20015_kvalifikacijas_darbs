<?php
include 'partials/header.php';

// check if user is admin
if (isset($_SESSION['user_is_admin']) && ($_SESSION['user_is_admin'] == true)) {
    //fetch users from database without current user
    $current_admin_id = $_SESSION['user-id'];

    $query = "SELECT * FROM users WHERE NOT id = $current_admin_id";
    $users = mysqli_query($connection, $query);
} else {
    header('location: ' . ROOT_URL . 'admin');
    die();
}
?>

<section class="dashboard">
    <!-- display error messages -->
    <?php // shows if add user was successful
    if (isset($_SESSION['add-user-success'])) : ?>
        <div class="alert__message success container">
            <p>
                <?= $_SESSION['add-user-success'];
                unset($_SESSION['add-user-success']) ?>
            </p>
        </div>

    <?php // shows if edit user was successful 
    elseif (isset($_SESSION['edit-user-success'])) : ?>
        <div class="alert__message success container">
            <p>
                <?= $_SESSION['edit-user-success'];
                unset($_SESSION['edit-user-success']) ?>
            </p>
        </div>

    <?php // shows if edit user was NOT successful 
    elseif (isset($_SESSION['edit-user'])) : ?>
        <div class="alert__message error container">
            <p>
                <?= $_SESSION['edit-user'];
                unset($_SESSION['edit-user']) ?>
            </p>
        </div>

    <?php // shows if delete user was NOT successful 
    elseif (isset($_SESSION['delete-user'])) : ?>
        <div class="alert__message error container">
            <p>
                <?= $_SESSION['delete-user'];
                unset($_SESSION['delete-user']) ?>
            </p>
        </div>
    <?php // shows if delete user was successful 
    elseif (isset($_SESSION['delete-user-success'])) : ?>
        <div class="alert__message success container">
            <p>
                <?= $_SESSION['delete-user-success'];
                unset($_SESSION['delete-user-success']) ?>
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
                        <a href="manage-users.php" class="active"><i class="uil uil-users-alt"></i>
                            <h5>Manage Users</h5>
                        </a>
                    </li>
                    <li>
                        <a href="add-project.php"><i class="uil uil-edit"></i>
                            <h5>Add Project</h5>
                        </a>
                    </li>
                    <li>
                        <a href="manage-projects.php"><i class="uil uil-list-ul"></i>
                            <h5>Manage Projects</h5>
                        </a>
                    </li>
                <?php endif ?>
            </ul>
        </aside>
        <main>
            <h2>Manage Users</h2>
            <?php if (mysqli_num_rows($users) > 0) : ?>
                <table>
                    <thead>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        <th>Admin</th>
                    </thead>
                    <tbody>
                        <?php while ($user = mysqli_fetch_assoc($users)) : ?>
                            <tr>
                                <td><?= "{$user['firstname']} {$user['lastname']}" ?></td>
                                <td><?= $user['username'] ?></td>
                                <td><a href="<?= ROOT_URL ?>admin/edit-user.php?id=<?= $user['id'] ?>" class="btn sm">Edit</a></td>
                                <td><a href="<?= ROOT_URL ?>admin/delete-user.php?id=<?= $user['id'] ?>" class="btn sm danger">Delete</a></td>
                                <td><?= $user['is_admin'] ? 'Yes' : 'No' ?></td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php else : ?>
                <div class="alert__message error"><?= "There are no users" ?> </div>
            <?php endif ?>
        </main>
    </div>
</section>

<?php
include '../partials/footer.php';
?>