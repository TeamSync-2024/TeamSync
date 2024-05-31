<?php

session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
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
</head>
<body>
  <div id="header_container"></div>

  <?php if (isset($_SESSION['user_id'])): ?>
    <div id="navigation_container"></div>
  <?php endif;?>

  <main class="vertical">
    <div class="center">
      <h1 class="slideIn" style="font-size: xxx-large">Καλωσήρθατε στο <span style="color: #2599ff">TeamSync</span></h1>
    </div>

    <div class="center">
      <h2>Συνεργασία χωρίς εμπόδια, οπουδήποτε και ανά πάσα στιγμή.</h2>
    </div>

    <div>
        <h2 class="accordion">Ενισχύστε την Παραγωγικότητα της Ομάδας σας</h2>
        <div class="panel">
          <p>Στο TeamSync, πιστεύουμε ότι οι σπουδαίες ομαδικές εργασίες ξεκινούν με την άψογη επικοινωνία και συνεργασία. Η πλατφόρμα μας έχει σχεδιαστεί για να φέρει την ομάδα σας κοντά και να βοηθήσει να επιτύχετε περισσότερα μαζί.</p>
        </div>
    </div>

    <div>
      <h2 class="accordion">Λειτουργίες Σχεδιασμένες για Επιτυχία</h2>
      <div class="panel">
        <ul>
          <li>
            <h3>Διαχείριση Εργασιών</h3>
            <p>
              Δημιουργήστε λίστες εργασιών, αναθέστε εργασίες, ορίστε προθεσμίες και παρακολουθήστε την πρόοδο τους. Η σωστή διαχείριση και ενημέρωση των εργασιών διασφαλίζει πως όλα τα μέλη της ομάδας βρίσκονται στο ίδιο μήκος κύματος και στόχος τους είναι η μόνο η επιτυχία. 
            </p>
          </li>
          <li>
            <h3>Αποστολή  Ειδοποιήσεων σε Πραγματικό Χρόνο</h3>
            <p>
              Μείνετε ενημερωμένοι κάθε στιγμή για τις λίστες και τις εργασίες που σας αφορούν. Το TeamSync, μέσω της υπηρεσίας <b><a href="https://simplepush.io/">Simplepush</a></b>, σας προσφέρει άμεση ενημέρωση για κάθε εργασία που σας ανατέθηκε στην κινητή σας συσκευή.
            </p>
          </li>
        </ul>
      </div>
    </div>

    <div>
      <h2 class="accordion">Γιατί να επιλέξετε το TeamSync;</h2>
        <div class="panel">
          <ul>
            <li>
              <h3>Φιλική προς το χρήστη διεπαφή</h3>
              <p>
                H πλατφόρμα μας είναι εύχρηστη, με σαφή και φιλικό σχεδιασμό που επιτρέπει εύκολη και αποτελεσματική συνεργασία.
              </p>
              </li>
            <li>
              <h3>Ασφάλεια και αξιοπιστία</h3>
              <p>
                Προτεραιότητα μας είναι η ασφάλεια των δεδομένων σας με προηγμένη κρυπτογράφηση και προστατευμένη αποθήκευση δεδομένων. Εμπιστευτείτε το TeamSync για την προστασία των δεδομένων σας.
              </p>
            </li>
            <li>
              <h3>Ευελιξία και προσαρμοστικότητα</h3>
              <p>
                Ανεξάρτητα από το εάν η ομάδα σας διαθέτει λίγα μέλη ή είστε ένας μεγάλος οργανισμός, το TeamSync προσαρμόζεται ανάλογα με τις απαιτήσεις σας.
              </p>
            </li>
            <li>
              <h3>Υποστήριξη 24/7</h3>
              <p>
                H εξειδικευμένη ομάδα υποστήριξής μας είναι διαρκώς στη διάθεσή σας για να σας βοηθήσει. Ζητήστε υποστήριξη οποτεδήποτε τη χρειαστείτε, μέρα ή νύχτα.
              </p>
              </li>
          </ul>
        </div>
    </div>

    <div>
      <h2 class="accordion">Ξεκινήστε Τώρα</h2>
      <div class="panel">
        <p>Γίνετε μέλος χιλιάδων ομάδων που επωφελούνται ήδη από το TeamSync. Εγγραφείτε τώρα και ανακαλύψτε πόσο απλή και αποτελεσματική μπορεί να είναι η ομαδική συνεργασία.</p>
        <div class="center">
          <div class="max_width">
            <a href="../public/register.html"><button>Εγγραφείτε Τώρα</button></a>
          </div>
        </div>
      </div>
    </div>

    </main>
  <div id="footer_container"></div>
</body>
</html>