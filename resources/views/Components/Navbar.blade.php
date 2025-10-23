<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rentify Navbar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* Rentify Navbar Styling*/
.navbar {
  transition: all 0.3s ease;
  background: #ffffff;
  border-bottom: 1px solid rgba(0,0,0,0.05);
}
.navbar.scrolled {
  box-shadow: 0 3px 12px rgba(0,0,0,0.08);
}
.navbar-brand {
  font-weight: 800;
  font-size: 1.6rem;
  color: #198754 !important;
  letter-spacing: 0.3px;
}

/* Nav Links Styling */
.nav-link {
  position: relative;
  font-weight: 500;
  color: #333 !important;
  transition: color 0.3s ease;
  padding: 0.6rem 1rem;
}
.nav-link::after {
  content: "";
  position: absolute;
  bottom: 0;
  left: 50%;
  width: 0;
  height: 2px;
  background-color: #198754;
  transition: all 0.3s ease;
  transform: translateX(-50%);
}
.nav-link:hover {
  color: #198754 !important;
}
.nav-link:hover::after {
  width: 60%;
}

/*Buttons Styling*/
.btn {
  font-weight: 600;
  border-radius: 8px;
  transition: all 0.3s ease;
}
.btn-outline-primary:hover,
.btn-outline-danger:hover {
  transform: translateY(-2px);
}
.btn-primary {
  background-color: #198754;
  border: none;
}
.btn-primary:hover {
  background-color: #157347;
}

/* Responsive Styling */

/* Large screens (Desktop) */
@media (min-width: 1200px) {
  .navbar-nav .nav-link {
    font-size: 1rem;
  }
  .btn {
    font-size: 0.95rem;
    padding: 0.45rem 1rem;
  }
}

/* Tablets (768px - 1199px) */
@media (max-width: 1199px) and (min-width: 768px) {
  .navbar-brand {
    font-size: 1.4rem;
  }
  .navbar-nav .nav-link {
    font-size: 0.95rem;
    padding: 0.5rem 0.8rem;
  }
  .btn {
    font-size: 0.9rem;
    padding: 0.4rem 0.9rem;
  }
  .navbar-collapse {
    justify-content: center;
  }
}

/* Mobile (below 768px) */
@media (max-width: 767px) {
  .navbar-collapse {
    background-color: #ffffff;
    border-radius: 12px;
    margin-top: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    padding: 12px;
  }
  .nav-link {
    display: block;
    padding: 10px 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
  }
  .nav-link:last-child {
    border-bottom: none;
  }
  .btn {
    width: 100%;
    margin-bottom: 15px;
    font-size: 0.95rem;
  }
  .navbar-brand {
    font-size: 1.3rem;
  }
  .navbar-collapse.show {
    animation: slideDown 0.3s ease;
  }
}

/* Subtle slide animation for mobile dropdown */
@keyframes slideDown {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

/*Hamburger animation*/
.navbar-toggler {
  border: none;
  background: transparent;
  outline: none;
}
.navbar-toggler:focus {
  box-shadow: none;
}
.hamburger {
  width: 24px;
  height: 2px;
  background-color: #198754;
  position: relative;
  transition: all 0.3s ease;
  margin: right 5px;
  cursor:'pointer'
}
.hamburger::before,
.hamburger::after {
  content: "";
  position: absolute;
  width: 24px;
  height: 2px;
  background-color: #198754;
  left: 0;
  transition: all 0.3s ease;
}
.hamburger::before {
  top: -8px;
}
.hamburger::after {
  top: 8px;
}
.navbar-toggler.active .hamburger {
  background-color: transparent;
}
.navbar-toggler.active .hamburger::before {
  transform: rotate(45deg);
  top: 0;
}
.navbar-toggler.active .hamburger::after {
  transform: rotate(-45deg);
  top: 0;
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg fixed-top py-3">
  <div class="container">
    <a class="navbar-brand" href="/">🏠 Rentify</a>

    <button class="navbar-toggler" type="button" aria-label="Toggle navigation">
      <span class="hamburger"></span>
    </button>

    <div class="collapse navbar-collapse text-center" id="mainNav">
      <ul id="dynamicLinks" class="navbar-nav ms-auto mb-2 mb-lg-0"></ul>
      <div id="dynamicButtons" class="ms-lg-3 mt-3 mt-lg-0 d-flex flex-column flex-lg-row"></div>
    </div>
  </div>
</nav>
<!-- 
<div style="margin-top: 90px;"></div> -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
/*on username navigation like if admin or if user or guest on landing page*/
const guestLinks = [
  { name: "Home", href: "/" },
  { name: "Why Rentify", href: "#why" },
  { name: "How It Works", href: "#how" }
];
const userLinks = [
  { name: "Properties", href: "/properties" },
  { name: "Booked Properties", href: "/bookings" }
];
const adminLinks = [
  { name: "Listing Properties", href: "/admin/dashboard" },
  { name: "View Bookings", href: "/admin/bookings" }
];
const guestButtons = [
  { name: "Login", href: "/login", class: "btn btn-outline-primary me-lg-2" },
  { name: "Sign Up", href: "/register", class: "btn btn-primary" }
];
const userButtons = [
  { name: "Logout", id: "logoutBtn", class: "btn btn-outline-danger" }
];
const adminButtons = [
  { name: "Logout", id: "adminLogoutBtn", class: "btn btn-outline-danger" }
];

// Render Navbar based on role
function renderNavbar(role = "guest") {
  const linksContainer = document.getElementById("dynamicLinks");
  const buttonsContainer = document.getElementById("dynamicButtons");
  linksContainer.innerHTML = "";
  buttonsContainer.innerHTML = "";

  let links = [];
  let buttons = [];

  if (role === "admin") {
    links = adminLinks;
    buttons = adminButtons;
  } else if (role === "user") {
    links = userLinks;
    buttons = userButtons;
  } else {
    links = guestLinks;
    buttons = guestButtons;
  }

  links.forEach(link => {
    const li = document.createElement("li");
    li.className = "nav-item";
    li.innerHTML = `<a class="nav-link" href="${link.href}">${link.name}</a>`;
    linksContainer.appendChild(li);
  });

  buttons.forEach(btn => {
    if (btn.href) {
      buttonsContainer.innerHTML += `<a href="${btn.href}" class="${btn.class}">${btn.name}</a>`;
    } else {
      buttonsContainer.innerHTML += `<button id="${btn.id}" class="${btn.class}">${btn.name}</button>`;
    }
  });
}

// Initialize Navbar
document.addEventListener("DOMContentLoaded", () => {
  const username = localStorage.getItem("username");
  let role = "guest";
  if (username === "Admin") role = "admin";
  else if (username && username.trim() !== "") role = "user";
  renderNavbar(role);

  // Logout handler
  const logout = () => {
    localStorage.removeItem("username");
    window.location.href = "/";
  };
  document.getElementById("logoutBtn")?.addEventListener("click", logout);
  document.getElementById("adminLogoutBtn")?.addEventListener("click", logout);
});

/* navbar behaviour & animation like on clicking hamburger on mobile toggling */
window.addEventListener("scroll", () => {
  const nav = document.querySelector(".navbar");
  if (window.scrollY > 20) nav.classList.add("scrolled");
  else nav.classList.remove("scrolled");
});

const toggler = document.querySelector(".navbar-toggler");
const navbarCollapse = document.getElementById("mainNav");
const bsCollapse = new bootstrap.Collapse(navbarCollapse, { toggle: false });

// toggle menu on clicking (open & close)
toggler.addEventListener("click", () => {
  toggler.classList.toggle("active");
  if (navbarCollapse.classList.contains("show")) {
    bsCollapse.hide();
  } else {
    bsCollapse.show();
  }
});

// to close menu when clicking any nav link or button
document.addEventListener("click", (e) => {
  if (navbarCollapse.classList.contains("show") && e.target.closest(".nav-link, .btn")) {
    bsCollapse.hide();
    toggler.classList.remove("active");
  }
});

// sync toggler state when menu hides
navbarCollapse.addEventListener("hidden.bs.collapse", () => {
  toggler.classList.remove("active");
});
</script>
</body>
</html>
