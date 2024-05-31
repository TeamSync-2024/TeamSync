<?php
require_once '../src/auth_check.php';
require_once '../src/config.php';
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
      <h1>Λίστες Εργασιών</h1>  
    </div>

    <div style="align-self: flex-end;">
      <input type="text" id="taskSearch" placeholder="Αναζήτηση λίστας...">
    </div>

    <div id="lists_container" class="horizontal_latest"></div>

    <div class="center">
        <a href="./create_list.php"><button>Δημιουργία Λίστας</button></a>    
    </div>

    <div class="center">
        <h2>Εργασίες που μου έχουν ανατεθεί </h2> 
    </div>

    <div class="center">
      <div id="tasks_container"></div>
    </div>

    <div class="horizontal">
            <div>
                <div class="vertical">
                    <div class="pending">
                        <h2 class="center">Σε Αναμονή</h2>
                    </div>
                    <div id="assigned_pending_tasks" class="vertical"></div>
                </div>
            </div>

            <div>
                <div class="vertical" >
                    <div class="progress">
                        <h2 class="center">Σε Εξέλιξη</h2>
                    </div>
                    <div id="assigned_in_progress_tasks" class="vertical"></div>
                </div>
            </div>
        </div>
    
    </main>
  <div id="footer_container"></div>
</body>
</html>
