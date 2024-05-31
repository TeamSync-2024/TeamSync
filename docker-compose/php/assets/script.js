// Header footer from header_footer.html
// Navigation bar from navigation_bar.html
function loadHeaderFooterNavigation() {
  const headerContainer = document.getElementById('header_container');
  const footerContainer = document.getElementById('footer_container');

  const navigationContainer = document.getElementById('navigation_container');

  fetch('../assets/header_footer.html')
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const html = parser.parseFromString(data, 'text/html');
      headerContainer.innerHTML = html.querySelector('header').outerHTML;
      footerContainer.innerHTML = html.querySelector('footer').outerHTML;

      // Theme toggle functionality
      const themeToggle = document.getElementById("themeToggle");
      const body = document.body;

      // Load theme preference from cookie
      const theme = document.cookie.replace(
        /(?:(?:^|.*;\s*)theme\s*=\s*([^;]*).*$)|^.*$/,
        "$1"
      );

      if (theme === "dark") {
        body.classList.add("dark-mode");
        themeToggle.innerHTML = "&#9728;"; // Sun icon
      } else {
        themeToggle.innerHTML = "&#9790;"; // Moon icon
      }

      themeToggle.addEventListener("click", () => {
        body.classList.toggle("dark-mode");
        const isDarkMode = body.classList.contains("dark-mode");
        themeToggle.innerHTML = isDarkMode ? "&#9728;" : "&#9790;"; // Toggle between sun and moon
        document.cookie = `theme=${isDarkMode ? "dark" : "light"}; path=/`;
      });
    })
    .catch(error => console.error('Error loading header and footer:', error));

    // navigation
    fetch('../assets/navigation_bar.html')
    .then(response => response.text())
    .then(data => {
      navigationContainer.innerHTML = data;
    })
    .catch(error => console.error('Error loading navigation:', error));
}

window.onload = loadHeaderFooterNavigation;

// Accordion functionality
document.addEventListener("DOMContentLoaded", () => {
  const accordions = document.querySelectorAll(".accordion");

  accordions.forEach((accordion) => {
    accordion.addEventListener("click", () => {
      accordion.classList.toggle("active");
      const panel = accordion.nextElementSibling;
      if (panel.style.maxHeight) {
        panel.style.maxHeight = null;
      } else {
        panel.style.maxHeight = panel.scrollHeight + "px";
      }
    });
  });
});

// validation

document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");
  
  form.addEventListener("submit", function (event) {
      const username = document.getElementById("username").value;
      const email = document.getElementById("email").value;
      const simplepushKey = document.getElementById("simplepush_key").value;
      
      // Regular expressions for validation
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      let valid = true;

      // Validate email
      if (!emailPattern.test(email)) {
          alert("Please enter a valid email address.");
          valid = false;
      }

      // Additional validation logic can be added here

      if (!valid) {
          event.preventDefault();
      }
  });
});


//table for user page

$(document).ready(function() {
  // Fetch the user content using AJAX
  $.ajax({
    url: '../src/get_user.php', // Path to your PHP script that returns the user content
    type: 'GET',
    dataType: 'json',
    success: function(data) {
      // Clear the container
      $('#user_content').empty();

      // Create table structure
      var table = $('<table>').addClass('user-table center');
      var tbody = $('<tbody>');
      
      // Define the attributes to display
      var attributes = ['first_name', 'last_name', 'username', 'email', 'simplepush_key'];
      var attributeNames = {
        'first_name': 'Όνομα',
        'last_name': 'Επώνυμο',
        'username': 'Username',
        'email': 'Email',
        'simplepush_key': 'SimplePush.io Key'
      };

      // Create table rows for each attribute
      $.each(attributes, function(index, attr) {
        var row = $('<tr>');
        row.append('<td>' + attributeNames[attr] + '</td>');
        $.each(data, function(userIndex, user) {
          row.append('<td>' + user[attr] + '</td>');
        });
        tbody.append(row);
      });

      table.append(tbody);
      $('#user_content').append(table);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.error('Error fetching task lists:', textStatus, errorThrown);
    }
  });
});


// Emfanisi lists container

$(document).ready(function() {
    // Fetch the task lists using AJAX
    $.ajax({
        url: '../src/get_lists.php', // Path to your PHP script that returns the task lists
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            // Clear the container
            $('#lists_container').empty();

            // Iterate over the data and create list items
            $.each(data, function(index, item) {
                var listItem = $('<div class="list">');
                listItem.append('<h2 class="center">' + item.title + '</h2>');
                listItem.append('<p>' + item.description + '</p>');
                listItem.append('<p><b>Ημερομηνία Δημιουργίας:</b> ' + item.created_at + '</p>');
                listItem.append('<a class="center" href="../public/tasks.php?list_id=' + encodeURIComponent(item.list_id) + '"><button>Προβολή Εργασιών</button></a>');
                listItem.append('<a href="../src/delete_list.php?list_id=' + encodeURIComponent(item.list_id) + '" class="center"><button class="red">&#128473;</button></a>')

                // Append the list item to the container
                $('#lists_container').append(listItem);
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching task lists:', textStatus, errorThrown);
        }
    });

    // Function to filter lists based on search input
    $('#taskSearch').on('input', function() {
      var searchText = $(this).val().toLowerCase();
      $('.list').each(function() {
          var listTitle = $(this).find('h2').text().toLowerCase();
          if (listTitle.includes(searchText)) {
              $(this).show();
          } else {
              $(this).hide();
          }
      });
    });

});



// emfanish tasks container assinged

$.ajax({
  url: '../src/get_independent_task.php',
  type: 'GET',
  dataType: 'json',
  success: function(data) {
      // Clear the containers
      $('#assigned_pending_tasks').empty();
      $('#assigned_in_progress_tasks').empty();

      // Iterate over the data and create list items
      $.each(data, function(index, item) {
          var listItem = $('<div class="list latest">');
          listItem.append('<h2 class="center">' + item.title + '</h2>');
          listItem.append('<p>' + item.description + '</p>');
          listItem.append('<p><b>Λίστα: </b>' + item.task_list_title + '</p>');
          var dueDate = new Date(item.due_date);
          var formattedDueDate = dueDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
          listItem.append('<p><b>Ημερομηνία Προθεσμίας:</b> ' + formattedDueDate + '</p>');
          listItem.append('<p><b>Κατάσταση:</b> ' + item.status + '</p>');
          listItem.append('<p><b>Ανάθεση σε:</b> ' + item.assigned_users + '</p>');
          listItem.append('<a class="center" href="../src/edit_task.php?task_id=' + encodeURIComponent(item.id) + '"><button>Επεξεργασία Εργασίας</button></a>');

          // Append the list item to the respective container based on status
          if (item.status === 'pending') {
              $('#assigned_pending_tasks').append(listItem);
          } else if (item.status === 'in-progress') {
              $('#assigned_in_progress_tasks').append(listItem);
          }
      });
  },
  error: function(jqXHR, textStatus, errorThrown) {
      console.error('Error fetching tasks:', textStatus, errorThrown);
  }
});



// emfanish all tasks container me bash to status tous

$(document).ready(function() {
  // Fetch the tasks using AJAX
  $.ajax({
      url: '../src/get_all_tasks.php',
      type: 'GET',
      dataType: 'json',
      success: function(data) {
          // Clear the containers
          $('#pending_tasks').empty();
          $('#in_progress_tasks').empty();
          $('#completed_tasks').empty();

          // Iterate over the data and create list items
          $.each(data, function(index, item) {
              var listItem = $('<div class="list">');

              // Add the hidden_id to the list item as hidden text
              listItem.append('<input type="hidden" value="' + item.id + '">');

              listItem.append('<h2 class="center">' + item.title + '</h2>');
              listItem.append('<p>' + item.description + '</p>');
              listItem.append('<p><b>Λίστα: </b>' + item.list_name + '</p>');

              // Convert the due_date string to a Date object
              var dueDate = new Date(item.due_date);

              // Format the date as desired (e.g., "May 15, 2024")
              var formattedDueDate = dueDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

              listItem.append('<p><b>Ημερομηνία Προθεσμίας:</b> ' + formattedDueDate + '</p>');
              listItem.append('<p><b>Κατάσταση:</b> ' + item.status + '</p>');
              listItem.append('<a class="center" href="../src/edit_task.php?task_id=' + encodeURIComponent(item.id) + '"><button>Επεξεργασία Εργασίας</button></a>');

              // Append the list item to the respective container based on status
              if (item.status === 'pending') {
                  $('#pending_tasks').append(listItem);
              } else if (item.status === 'in-progress') {
                  $('#in_progress_tasks').append(listItem);
              } else if (item.status === 'completed') {
                  $('#completed_tasks').append(listItem);
              }
          });
      },
      error: function(jqXHR, textStatus, errorThrown) {
          console.error('Error fetching tasks:', textStatus, errorThrown);
      }
  });

// Function to filter tasks based on search input and status
  $('#taskSearch, #statusFilter').on('input change', function() {
    var searchText = $('#taskSearch').val().toLowerCase();
    var selectedStatus = $('#statusFilter').val();
    
    $('.list').each(function() {
        var taskTitle = $(this).find('h2').text().toLowerCase();
        var taskDescription = $(this).find('p').eq(0).text().toLowerCase();
        var taskStatus = $(this).find('p:contains("Κατάσταση:")').text().trim().split(': ')[1].toLowerCase();
        
        var matchesSearch = taskTitle.includes(searchText) || taskDescription.includes(searchText);
        var matchesStatus = selectedStatus === 'all' || taskStatus === selectedStatus;

        if (matchesSearch && matchesStatus) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
  });


});

// εμφανιση τασκ απο συγκεκριμενη λιστα

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
            $('#list_tasks_container').empty();

            // Iterate over the data and create list items
            $.each(data, function(index, item) {
                var listItem = $('<div class="list">');
                // Add the hidden_id to the list item as hidden text
                listItem.append('<input type="hidden" value="' + item.id + '">');
                listItem.append('<h2 class="center">' + item.title + '</h2>');
                listItem.append('<p>' + item.description + '</p>');
                // Convert the due_date string to a Date object
                var dueDate = new Date(item.due_date);
                // Format the date as desired (e.g., "May 15, 2024")
                var formattedDueDate = dueDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                listItem.append('<p><b>Ημερομηνία Προθεσμίας:</b> ' + formattedDueDate + '</p>');
                listItem.append('<p><b>Κατάσταση:</b> ' + item.status + '</p>');
                listItem.append('<a class="center" href="../src/edit_task.php?task_id=' + encodeURIComponent(item.id) + '"><button>Επεξεργασία Εργασίας</button></a>');
                          
                // Add delete button
                var deleteButton = $('<a href="../src/delete_task.php?task_id=' + encodeURIComponent(item.id) + '" class="center"><button class="red">&#128473;</button></a>');
                listItem.append(deleteButton);

                // Append the list item to the container
                $('#list_tasks_container').append(listItem);
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching tasks:', textStatus, errorThrown);
        }
    });
});