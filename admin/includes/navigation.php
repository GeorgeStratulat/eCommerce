<nav class="navbar navbar-default navbar-fixed-top">
  	<div class="container">
  	 <a href="/tutorial/admin/index.php" class="navbar-brand">Shaunta's Boutique Admin</a>
  		<ul class="nav navbar-nav">
      
      <li><a href="/tutorial/admin/brands.php">Brands</a></li>
      <li><a href="/tutorial/admin/categories.php">Categories</a></li>
      <li><a href="/tutorial/admin/products.php">Products</a></li>
      <li><a href="/tutorial/admin/restore.php">Archived products</a></li>
      <?php if(has_permission('admin')): ?>
          <li><a href="/tutorial/admin/users.php">Users</a></li>
      <?php endif; ?>
  		<li class="dropdown right" style="position:absolute;right:20px;">
        <a href="#" class="dropdown-toggle " data-toggle="dropdown">Hello <?=$user_data['first'];?>!
          <span class="caret"></span>
        </a>  
        <ul class="dropdown-menu" role="menu">
          <li><a href="change_password.php">Change password</a></li>
          <li><a href="logout.php">Log out</a></li>
        </ul>
      </li>
  	</div> 
  </nav>