<?php
require_once '../src/auth_check.php';
require_once '../src/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Lists</title>
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
    <h1>Task Lists</h1>
    <div id="lists-container"></div>

    <a href="./create_list.php">
        <button>Create List</button>
    </a>

    <script>
        $(document).ready(function() {
            // Fetch the task lists using AJAX
            $.ajax({
                url: '../src/get_lists.php', // Path to your PHP script that returns the task lists
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
                        listItem.append('<p>Created At: ' + item.created_at + '</p>');
                        listItem.append('<a href="../public/tasks.php?list_id=' + encodeURIComponent(item.list_id) + '">View Tasks</a>');

                        // Append the list item to the container
                        $('#lists-container').append(listItem);
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error fetching task lists:', textStatus, errorThrown);
                }
            });
        });
    </script>
    <h2>Assigned to me </h2>
<div id="tasks-container"></div>

<script>
    $.ajax({
        url: '../src/get_independent_task.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#tasks-container').empty();
            $.each(data, function(index, item) {
                var listItem = $('<div class="list">');
                listItem.append('<h2>' + item.title + '</h2>');
                listItem.append('<p>' + item.description + '</p>');
                var dueDate = new Date(item.due_date);
                var formattedDueDate = dueDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                listItem.append('<p>Finished By: ' + formattedDueDate + '</p>');
                listItem.append('<p>Status: ' + item.status + '</p>');
                listItem.append('<p>Assigned To: ' + item.assigned_users + '</p>');
                listItem.append('<a href="../src/edit_task.php?task_id=' + encodeURIComponent(item.id) + '">View Task</a>');
                $('#tasks-container').append(listItem);
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching tasks:', textStatus, errorThrown);
        }
    });
</script>
</body>
</html>
