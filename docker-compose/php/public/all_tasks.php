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
  <meta name="author" content="voltmaister & marked-d">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TeamSync</title>
  <link rel="stylesheet" href="../assets/styles.css">
  <script src="../assets/script.js" defer></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="header_container"></div>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div id="navigation_container"></div>
    <?php endif;?>
    
    <main class="vertical">
        <div class="center">
            <h1>Οι Εργασίες Μου</h1>
        </div>

        <div style="align-self: flex-end;">
            <div class="max_width" style="">
                <input type="text" id="taskSearch" placeholder="Αναζήτηση εργασιών...">
            </div>
            <div class="max_width">
                <select id="statusFilter">
                    <option value="all">Όλες οι καταστάσεις</option>
                    <option value="pending">Σε Αναμονή</option>
                    <option value="in-progress">Σε Εξέλιξη</option>
                    <option value="completed">Ολοκληρωμένη</option>
                </select>
            </div>
        </div>

        <div class="horizontal">
            <div>
                <div class="vertical">
                    <div class="pending">
                        <h2 class="center">Σε Αναμονή</h2>
                    </div>
                    <div id="pending_tasks" class="vertical"></div>
                </div>
            </div>

            <div>
                <div class="vertical" >
                    <div class="progress">
                        <h2 class="center">Σε Εξέλιξη</h2>
                    </div>
                    <div id="in_progress_tasks" class="vertical"></div>
                </div>
            </div>

            <div>
                <div class="vertical">
                    <div class="completed">
                        <h2 class="center">Ολοκληρωμένη</h2>
                    </div>
                    <div id="completed_tasks" class="vertical"></div>
                </div>
            </div>
        </div>

    </main>
  <div id="footer_container"></div>
</body>
</html>