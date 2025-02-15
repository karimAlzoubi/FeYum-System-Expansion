document.addEventListener("DOMContentLoaded", function () {
    // Select the dark mode toggle button by its ID
    const darkModeToggle = document.getElementById("darkModeToggle");
    // Select the icon within the dark mode toggle button
    const darkModeIcon = document.getElementById("darkModeIcon");
    // Reference to the <body> element
    const body = document.body;
    // Reference to the <html> element
    const html = document.documentElement;
    // Select all elements that should be affected by dark mode
    const elements = document.querySelectorAll(
        ".main-wrapper, .content-wrapper, .page-container, .container, .dashboard-section, .center-box, .login-box, .card, .job-card, .message-card, .sidebar, .action-buttons, .welcome-card, .cards-container, .cardFirstPage, .card, .message-card, .message-box, .job-card, .details-btn, .search-btn, .back-btn, .filter-btn, .submit-button, .login-button, .dropdown-content, .buttons, .track-container, .track-form, .back-button, .track-button, .login-form, .modal, .modal-content, .success-icon, .status-card, .button-container, .custom-button, .status-card, .info-item, .label, .value, .status-text, .button-container, .custom-button, .login-container, .links, .forgot-password"
    );
  
    // Check if dark mode was previously enabled by the user
    if (localStorage.getItem("darkMode") === "enabled") {
        // Add the 'dark-mode' class to the <body> element
        body.classList.add("dark-mode");
        // Add the 'dark-mode' class to the <html> element
        html.classList.add("dark-mode");
        // Add the 'dark-mode' class to all selected elements
        elements.forEach(el => el.classList.add("dark-mode"));
        // Replace the moon icon with a sun icon to indicate dark mode is active
        darkModeIcon.classList.replace("bi-moon", "bi-sun");
    }
  
    // Add a click event listener to the dark mode toggle button
    darkModeToggle.addEventListener("click", () => {
        // Toggle the 'dark-mode' class on the <body> element
        body.classList.toggle("dark-mode");
        // Toggle the 'dark-mode' class on the <html> element
        html.classList.toggle("dark-mode");
        // Toggle the 'dark-mode' class on all selected elements
        elements.forEach(el => el.classList.toggle("dark-mode"));
  
        // Determine the current mode based on the presence of the 'dark-mode' class
        const mode = body.classList.contains("dark-mode") ? "enabled" : "disabled";
        // Save the user's preference for dark mode in localStorage
        localStorage.setItem("darkMode", mode);
  
        // Toggle between moon and sun icons to reflect the current mode
        darkModeIcon.classList.toggle("bi-moon");
        darkModeIcon.classList.toggle("bi-sun");
    });
});
