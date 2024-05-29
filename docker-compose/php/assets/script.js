// Header footer from header_footer.html
function loadHeaderFooter() {
  const headerContainer = document.getElementById('header_container');
  const footerContainer = document.getElementById('footer_container');

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
}

window.onload = loadHeaderFooter;

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

  // Get the form element
  const form = document.getElementById('registrationForm');
});