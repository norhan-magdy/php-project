  <div class="sidebar">
    <h3>Restaurant</h3>
    <ul>
      <li>
        <a class="active" href="index.php">
          <i class="fa-regular fa-chart-bar fa-fw"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li>
        <a href="./manage_inventory.php">
          <i class="fa-solid fa-box"></i>
          <span>Inventory</span>
        </a>
      </li>
      <li>
        <a href="./manage_menu.php">
          <i class="fa-solid fa-book-open fa-fw"></i>
          <span>Menu</span>
        </a>
      </li>
      <li>
        <a href="./manage_orders.php">
          <i class="fa-solid fa-utensils fa-fw"></i>
          <span>Orders</span>
        </a>
      </li>
      <li>
        <a href="./manage_reservations.php">
          <i class="fa-regular fa-calendar fa-fw"></i>
          <span>Reservations</span>
        </a>
      </li>

      <li>
        <a href="./manage_users.php">
          <i class="fa-solid fa-users fa-fw"></i>
          <span>Users</span>
        </a>
      </li>
    </ul>
  </div>
  <script>
    const currentPage = window.location.pathname.split('/').pop();

    const sidebarLinks = document.querySelectorAll(".sidebar ul li a");

    sidebarLinks.forEach(link => {
      link.classList.remove("active");
      console.log(link.getAttribute("href"));
      if (link.getAttribute("href").includes(currentPage)) {
        console.log(link);

        link.classList.add("active");
      }
    });
  </script>