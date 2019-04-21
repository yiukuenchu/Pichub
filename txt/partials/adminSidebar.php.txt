<!-- This is the code for the sidebar in the admin panel -->
<div class="sidebar">
  <ul class="sidebar-container">
    <li><a class="<?php if(PAGE_TITLE == 'Dashboard') echo 'active'; ?>" href="<?php path('/admin/'); ?>" title="View the admin dashboard"><span class="fas fa-tachometer-alt"></span></a></li>
    <li><a class="<?php if(PAGE_TITLE == 'All Orders') echo 'active'; ?>" href="<?php path('/admin/orders/'); ?>" title="View all orders"><span class="fas fa-shopping-bag"></span></a></li>
    <li><a class="<?php if(PAGE_TITLE == 'Items') echo 'active'; ?>" href="<?php path('/admin/items/'); ?>" title="Edit item details"><span class="fas fa-edit"></span></a></li>
  </ul>
</div>
