<!DOCTYPE html>
<html>
  <head>
    <title>The Booth</title>
    <!-- Set charset and viewport -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Load bootstrap icons and stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="../sharedcode/styles.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<?php 
    if (!isset($_COOKIE['admin_account_info'])) {
        header("Location: admin_login.php");
        die();
    }
    /*
        $id = $row['id'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $email = $row['email'];
        $type = $row['type'];
        $created = $row['created'];
    */
    
    if (!isset($_GET['id']) || !isset($_GET['first_name']) || !isset($_GET['last_name']) || !isset($_GET['email']) || !isset($_GET['type']) || !isset($_GET['created'])) {
        header("Location: admin_manage_employees.php");
    }

    $id = $_GET['id'];
    $emp_first_name = $_GET['first_name'];
    $emp_last_name = $_GET['last_name'];
    $email = $_GET['email'];
    $type = $_GET['type'];
    $created = $_GET['created'];
?>
<body>
    <!-- Navigation-->
    <div id="navbar-container"><?php include("../sharedcode/admin_nav.php"); ?></div>
    <br>
    <div class="text-center">
        <h2><b>Edit Employee Account</b></h2><br>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <h4>Editing employee #<?php echo $id ?></h4><br>
                    <div class="form-control" id="edit_employee_form">
                        <form id="admin_update_employee_account" enctype="multipart/form-data"> <!-- requires php file -->
                            <?php 
                                echo <<<HTML
                                    <input class="form-control" type="hidden" value=$id name="id">
                                    <label class="form-label" for="first_name">First Name:</label><br>
                                    <input class="form-control" type="text" id="first_name" name="first_name" required value=$emp_first_name><br><br>
                                    <label class="form-label" for="last_name">Last Name:</label><br>
                                    <input class="form-control" type="text" id="last_name" name="last_name" required value=$emp_last_name><br><br>
                                    <label class="form-label" for="email">Email:</label><br>
                                    <input class="form-control" type="email" id="email" name="email" required value=$email><br><br>
                                    <input class="form-control btn btn-success" type="submit" value="Save Changes">
                                HTML;
                            ?>
                        </form><br>
                        <button class="btn btn-danger" id="delete_button" data-account-id="<?php echo $id; ?>">Delete Account</button>
                        <div id="error_message"></div>
                    </div>
                </div>
            </div>
        </div>
        <div id="success_message"></div>
    </div>
    
    <!-- Scripts -->
    <script src="../sharedcode/scripts.js"></script>
    <script src="../_js/admin.js"></script>
    <script>
    $("#delete_button").click(function() {
        var confirmDelete = confirm('Are you sure you want to delete this account? This action cannot be undone.');
        if (confirmDelete) {
            var accountId = $(this).data('account-id');
            $.ajax({
                url: '../_php/r_admin_delete_manager_account.php',
                type: 'POST',
                data: {id: accountId},
                success: function(response) {
                    $('#success_message').text('Account deleted successfully.');
                    setTimeout(function() {
                        window.location.replace("admin.php");
                    }, 2000);
                },
                error: function() {
                    $('#error_message').text('There was an error deleting the account.');
                }
            });
        }
    });
    </script>
</body>
</html>