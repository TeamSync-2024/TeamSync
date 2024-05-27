<?php
require_once '../src/auth_check.php';
require_once '../src/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$list_id = isset($_GET['list_id']) ? $_GET['list_id'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .list {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Tasks</h1>
    <div id="lists-container"></div>

    <a href="./create_task.php?list_id=<?php echo htmlspecialchars($list_id); ?>">
        <button>Create Task</button>
    </a>

    <script>
        $(document).ready(function() {
            // Get the list_id from the URL query string
            var urlParams = new URLSearchParams(window.location.search);
            var listId = urlParams.get('list_id');

            // Fetch the tasks using AJAX
            $.ajax({
                url: '../src/get_tasks.php?list_id=' + listId, // Include the list_id as a query parameter
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Clear the container
                    $('#lists-container').empty();

                    // Iterate over the data and create list items
                    $.each(data, function(index, item) {
                        var listItem = $('<div class="list">');
                        listItem.append('<h2>' + item.title + '</h2>');
                        listItem.append('<p>' + item.description + '</p>');

                        // Convert the due_date string to a Date object
                        var dueDate = new Date(item.due_date);
                        // Format the date as desired (e.g., "May 15, 2024")
                        var formattedDueDate = dueDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                        listItem.append('<p>Finished By: ' + formattedDueDate + '</p>');

                        listItem.append('<p>Status: ' + item.status + '</p>');
                        // Append the list item to the container
                        $('#lists-container').append(listItem);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error fetching tasks:', textStatus, errorThrown);
                }
            });
        });
    </script>

</body>
</html>