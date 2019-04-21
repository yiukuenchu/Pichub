<noscript>
  <!-- Makes sure that you can see menu items if javascript is disabled for some reason -->
  <style>
    .navbar-nav {
      flex-direction: row;
    }
    .nav-item {
      margin-left: .5rem;
    }
    .navbar-toggler {
      display: none;
    }
  </style>
</noscript>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark fixed-top" id="navbar">
  <a class="navbar-brand" href="<?php path('/'); ?>"><span class="fas fa-images"></span> Framed</a>
  <button class="navbar-toggler" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" @click="toggleCollapsed">
  <span class="navbar-toggler-icon"></span>
  </button>
  <transition name="expand">
    <div class="collapse navbar-collapse show justify-content-between" v-show="!collapsed">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?php if(PAGE_TITLE == 'Home') echo 'active'; ?>" href="<?php path('/'); ?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if(PAGE_TITLE == 'About') echo 'active'; ?>" href="<?php path('/about/'); ?>">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if(PAGE_TITLE == 'Store') echo 'active'; ?>" href="<?php path('/store/'); ?>">Store</a>
        </li>
      </ul>
      <ul class="navbar-nav">
        <!-- Only show these items if the user is logged in -->
        <?php if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true): ?>
          <li class="nav-item">
            <!-- Profile dropdown, uses BootstrapVue, Vue.js to work -->
            <b-nav-item-dropdown right role="menu">
              <template slot="button-content">
                <span class="fas fa-user"></span> <?php echo $_SESSION['username']; ?>
              </template>
              <!-- Only show the link to the admin panel if the user is an admin -->
              <?php if($_SESSION['role'] == "Admin"): ?>
                <b-dropdown-item class="bg-transparent" href="<?php path('/admin/'); ?>"><span class="fas fa-toolbox"></span> Admin</b-dropdown-item>
              <?php endif; ?>
              <b-dropdown-item class="bg-transparent" href="<?php path('/profile/'); ?>"><span class="fas fa-cog"></span> Settings</b-dropdown-item>
              <b-dropdown-item class="bg-transparent" href="<?php path('/profile/orders/'); ?>"><span class="fas fa-shopping-bag"></span> My Orders</b-dropdown-item>
              <div class="dropdown-divider"></div>
              <b-dropdown-item class="bg-transparent" href="<?php path('/logout/'); ?>"><span class="fas fa-sign-out-alt"></span> Sign Out</b-dropdown-item>
            </b-nav-item-dropdown>
          </li>
          <li class="nav-item d-flex align-items-center">
            <a href="<?php path('/cart/'); ?>" class="fa-layers fa-fw nav-link">
              <span class="fas fa-shopping-cart" title="View your cart"></span><noscript>Cart</noscript>
              <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <span class="fa-layers-counter" style="background:Tomato"></span>
              <?php endif; ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php path('/favorites/'); ?>"><span title="View your favorites" class="fas fa-heart"></span><noscript>Favorites</noscript></a>
          </li>

        <?php else: ?>
          <!-- Show these if the user is not logged in -->
          <div class="ml-auto">
            <a class="btn btn-secondary navbar-btn" href="<?php path('/login/'); ?>">Sign In</a>
            <a class="btn btn-success navbar-btn" href="<?php path('/register/'); ?>">Register</a>
          </div>
        <?php endif; ?>
      </ul>
    </div>
  </transition>
</nav>
