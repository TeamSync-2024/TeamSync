<?php

require_once 'auth_check.php';

// Display user information
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="author" content="voltmaister & marked-d">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TeamSync</title>
  <link rel="stylesheet" href="../assets/styles.css">
  <script src="../assets/script.js" defer></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <div id="header_container"></div>
  <main class="vertical">
    
    <div class="center">
      <h1>Καλωσήρθατε, <?php echo $username; ?>!</h1>
    </div>
    
    <div class="center">
      <p>
        Αυτή είναι η σελίδα του προφίλ σας. Μπορείτε να επεξεργαστείτε τα στοιχεία σας, να τα διαγράψετε ή να κάνετε αποσύνδεση.
      </p>
    </div>

    <div id='lists-container'>
          <script>
            $(document).ready(function() {
                // Fetch the task lists using AJAX
                $.ajax({
                    url: '../src/get_user.php', // Path to your PHP script that returns the task lists
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Clear the container
                        $('#lists-container').empty();

                        // Iterate over the data and create list items
                        $.each(data, function(index, item) {
                            var listItem = $('<div class="list">');
                            listItem.append('<h2>Όνομα:' + item.first_name + '</h2>');
                            listItem.append('<p>Επίθετο: ' + item.last_name + '</p>');
                            listItem.append('<p>Username: ' + item.username + '</p>');
                            listItem.append('<p>Email: ' + item.email + '</p>');  
                            listItem.append('<p>SimplePush.io Key: ' + item.simplepush_key + '</p>');
                            $('#lists-container').append(listItem);
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error fetching task lists:', textStatus, errorThrown);
                    }
                });
            });
          </script>
    </div>

    <div class="horizontal">

      <div class="max_width">
        <a href="../src/edit_profile.php"><button>Επεξεργασία Προφίλ</button></a>
      </div>
      <div class="max_width">
        <form action="delete_user.php" method="post">
            <button class="red" type="submit" value="Delete Profile">Διαγραφή Προφίλ</button>
        </form> 
      </div>
      <div class="max_width">
        <a href="../public/logout.php"><button class="orange">Αποσύνδεση</button></a>
      </div>

    </div>

    </main>
  <div id="footer_container"></div>
  <div id="footer_container"></div>
</body>
</html>