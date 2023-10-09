<?php
// Function to upload image with retries
function uploadImageWithRetry($faultId, $image, $viewType, $retryCount = 5, $retryDelay = 200000) {
    // Database connection (replace with the path to your SQLite database file)
    $database_file = "nokia_model_level.db"; // Replace with the path to your SQLite database file
    $db = new SQLite3($database_file);

    // Check for database connection errors
    if (!$db) {
        die("Connection failed: " . $db->lastErrorMsg());
    }

    // Determine the column name based on the selected view type
    $columnName = '';
    switch ($viewType) {
        case 'full':
            $columnName = 'full_view';
            break;
        case 'schematic':
            $columnName = 'schematic_view';
            break;
        case 'description':
            $columnName = 'description_view';
            break;
        default:
            // Handle invalid view types or set a default view type
            $columnName = 'full_view';
            break;
    }

    // Prepare and execute the SQL statement to insert the image with retries
    $stmt = null;
    $retry = 0;
    while ($retry < $retryCount) {
        $stmt = $db->prepare("INSERT INTO faulty_id (failure_id_model, $columnName) VALUES (:faultId, :imageData)");
        if ($stmt) {
            $stmt->bindParam(':faultId', $faultId, SQLITE3_TEXT);
            $imageData = file_get_contents($image['tmp_name']);
            $stmt->bindParam(':imageData', $imageData, SQLITE3_BLOB);

            if ($stmt->execute()) {
                $stmt->close();
                $db->close();
                echo "Image uploaded successfully!";
                return;
            } else {
                // Retry after a delay if the execution fails
                usleep($retryDelay);
            }
        } else {
            echo "Error preparing statement: " . $db->lastErrorMsg();
            break;
        }

        $retry++;
    }

    // Close the database connection
    $stmt->close();
    $db->close();

    echo "Error uploading image: database is locked after retries";
}

// Check if the form was submitted
if (isset($_POST['upload'])) {
    // Retrieve the input values
    $faultId = $_POST['faultId'];
    $image = $_FILES['image'];
    $viewType = $_POST['viewType']; // Assuming you have a form field for view type

    // Upload the image with retries
    uploadImageWithRetry($faultId, $image, $viewType);
}
?>
