<?php
// Ensure IN_CODE is defined for included files to process
define("IN_CODE", 1);
try {
    include("dbconfig.php");
    include("functions.php");
    $con = mysqli_connect($dbserver, $dbuser, $dbpass, $dbname) or die("<br>Cannot connect to DB.\n");

    // Check for ID
    if (!isset($_POST['id'])) {
        return_json_error("Form submit error: ID not received.");
    }

    // Get the ID from script
    $id = $_POST['id'];

    // DELETE ACCOUNT FROM DATABASE //////////////////////////////////////////////////////

    // Prepare statement for deletion
    $query = "DELETE FROM store_template.Employee WHERE id = ?;";
    $stmt = $con->prepare($query);
    if (!$stmt) {
        return_json_error("Delete failed (prepared statement failed): (" . $con->errno . ") " . $con->error);
    }

    $stmt->bind_param('i', $id);

    // Execute the statement
    if (!$stmt->execute()) {
        return_json_error("Delete failed (execute failed): (" . $stmt->errno . ") " . $stmt->error);
    }

    // Check if a row was deleted
    if ($stmt->affected_rows === 0) {
        return_json_error("Delete failed, no account found with the provided ID.");
    } else {
        // SUCCESS
        return_json_success("Account deleted successfully.");
    }

    $stmt->close();
} catch (Exception $e) {
    return_json_error('Caught exception: ' . $e->getMessage());
}
?>
