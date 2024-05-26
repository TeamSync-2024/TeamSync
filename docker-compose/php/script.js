// Header footer template
function loadHeaderFooter() {
  const headerContainer = document.getElementById('header-container');
  const footerContainer = document.getElementById('footer-container');

  fetch('header-footer.html')
    .then(response => response.text())
    .then(data => {
      const parser = new DOMParser();
      const html = parser.parseFromString(data, 'text/html');
      headerContainer.innerHTML = html.querySelector('header').outerHTML;
      footerContainer.innerHTML = html.querySelector('footer').outerHTML;

      // Theme toggle functionality
      const themeToggle = document.getElementById("themeToggle");
      const body = document.body;

      themeToggle.addEventListener("click", () => {
        body.classList.toggle("dark-mode");
        const isDarkMode = body.classList.contains("dark-mode");
        themeToggle.textContent = isDarkMode ? "Light Mode" : "Dark Mode"; 
        document.cookie = `theme=${isDarkMode ? "dark" : "light"}; path=/`;
      });

    })
    .catch(error => console.error('Error loading header and footer:', error));
}

window.onload = loadHeaderFooter;

// Accordion functionality
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

// Load theme preference from cookie
const theme = document.cookie.replace(
  /(?:(?:^|.*;\s*)theme\s*=\s*([^;]*).*$)|^.*$/,
  "$1"
);
if (theme === "dark") {
  body.classList.add("dark-mode");
  themeToggle.textContent = "Light Mode"; // Set button text based on loaded mode
} else {
  themeToggle.textContent = "Dark Mode"; // Set button text based on loaded mode
}

// Get the form element
const form = document.getElementById('registrationForm');