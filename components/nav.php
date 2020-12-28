<nav>
    <?php
    if (!isset($_SESSION['username'])) {
    ?>
        <ul class="account_selection">
            <li><a href="loginForm.php"><i class="fa fa-sign-in" aria-hidden="true"></i>Sign In</a></li>
            <li><a href="registerForm.php"><i class="fa fa-user-plus" aria-hidden="true"></i>Register</a></li>
        </ul>

        <?php
    } else {

        $sql = "SELECT * FROM account_user WHERE id = " . $_SESSION['id'];

        if ($result = $conn->query($sql)) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
        ?>
                    <div class="container p-0 justify-content-end">



                        <div class="btn-group">
                            <div class="form-control mt-2" style="color: #33cc00"><?php echo $row['name_account'] ?>

                            </div>

                        </div>
                        <a class="dropdown-item mt-2" href="structure/api_logout.php" style="color: #0066ff">
                            <img src="images/155.png" size="50 pixel" class="img-fluid icon-2 mr-2 mt-0-5">
                            Logout
                        </a>

                    </div><!-- container -->
    <?php
                }
            }
        }
    }
    ?>

</nav>