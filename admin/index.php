<?php
include 'partials/header.php';

// featch current users bugs from database
$current_user_id = $_SESSION['user-id'];
$query = "SELECT * FROM bugs WHERE bugs.reporter_id = $current_user_id ORDER BY id DESC";
$bugs = mysqli_query($connection, $query);
?>

<section class="dashboard">
    <!-- display error messages -->
    <?php // shows if add bug ticket was successful 
    if (isset($_SESSION['add-bug-success'])) : ?>
        <div class="alert__message success container">
            <p>
                <?= $_SESSION['add-bug-success'];
                unset($_SESSION['add-bug-success']) ?>
            </p>
        </div>
    <?php // shows if add bug ticket was not successful 
    elseif (isset($_SESSION['add-bug'])) : ?>
        <div class="alert__message error container">
            <p>
                <?= $_SESSION['add-bug'];
                unset($_SESSION['add-bug']) ?>
            </p>
        </div>
    <?php // shows if edit bug ticket was successful
    elseif (isset($_SESSION['edit-bug-success'])) : ?>
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
    <div class="container dashboard__container">
        <aside>
            <ul>
                <li>
                    <a href="add-bug.php"><i class="uil uil-pen"></i>
                        <h5>Add Bug Ticket</h5>
                    </a>
                </li>
                <li>
                    <a href="index.php" class="active"><i class="uil uil-postcard"></i>
                        <h5>Manage Bug Tickets</h5>
                    </a>
                </li>
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
                        <a href="manage-projects.php"><i class="uil uil-list-ul"></i>
                            <h5>Manage Projects</h5>
                        </a>
                    </li>
                <?php endif ?>
            </ul>
        </aside>
        <main>
            <h2>Manage Bug Tickets</h2>
            <!-- check if there are any bugs -->
            <?php if (mysqli_num_rows($bugs) > 0) : ?>
                <table>
                    <thead>
                        <th>Title</th>
                        <th>Project</th>
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

                            // get priority from priority table using project_id
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
                                <td><a href="<?= ROOT_URL ?>admin/bug.php?id=<?= $bug['id'] ?>"><?= $bug['title'] ?></a></td>
                                <td><?= $project['title'] ?></td>
                                <td><?= $priority['title'] ?></td>
                                <td> <?= date("d/m/Y", strtotime($bug['date_time'])) ?> </td>
                                <td><?= $status['title'] ?></td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php else : ?>
                <div class="alert__message error"><?= "There are no bug tickets" ?> </div>
            <?php endif ?>
        </main>
    </div>
</section>

<?php
include '../partials/footer.php';
?>