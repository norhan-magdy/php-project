<div class="sidebar bg-white p-20 p-relative">
  <h3 class="p-relative txt-c mt-0 d-none d-md-block ">Restaurant</h3>
  <ul>
    <li>
      <a class="active d-flex align-center fs-14 c-black rad-6 p-10" href="index.php">
        <i class="fa-regular fa-chart-bar fa-fw mr-10"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <li>
      <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="./manage_special_offers.php">
        <i class="fa-solid fa-star mr-10"></i>
        <span>Special Offers</span>
      </a>
    </li>
    <li>
      <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="./manage_inventory.php">
        <i class="fa-solid fa-box mr-10"></i>
        <span>Inventory</span>
      </a>
    </li>
    <li>
      <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="./manage_menu.php">
        <i class="fa-solid fa-book-open fa-fw mr-10"></i>
        <span>Menu</span>
      </a>
    </li>
    <li>
      <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="./manage_orders.php">
        <i class="fa-solid fa-utensils fa-fw mr-10"></i>
        <span>Orders</span>
      </a>
    </li>
    <li>
      <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="./manage_reservations.php">
        <i class="fa-regular fa-calendar fa-fw mr-10"></i>
        <span>Reservations</span>
      </a>
    </li>
    <li>
      <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="./manage_users.php">
        <i class="fa-solid fa-users fa-fw mr-10"></i>
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
    if (link.getAttribute("href").includes(currentPage)) {
      link.classList.add("active");
    }
  });
</script>