    
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-1 shadow-lg">

    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="/bit/common/home.php">
      <h5><strong><i class="CK_color mr-2">CK </i></strong>Computers</h5>
    </a>

      <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

    <!--<input class="form-control form-control-dark w-100 rounded-0 border-0 " type="text" placeholder="Search" aria-label="Search">-->
    
    <div class="navbar-nav">

        <div class="nav-item text-nowrap">

          <span class="text-white"><i class="fa fa-user-circle"></i> <?php echo $_SESSION['user'];?></span>
            <a class="text-white text-decoration-none px-3" href="/bit/logout.php?id=logout">
              <i class="fa fa-sign-out" aria-hidden="true"></i> logout</a> 
        </div>

    </div>

</header>

<div class="container-fluid">
  <div class="row">
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse shadow">
      <div class="position-sticky pt-3 sidebar-sticky">

            <ul class="nav flex-column">
              <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="/bit/common/home.php">
                      <span data-feather="home" class="align-text-bottom"></span>
                      Home 
                  </a>
              </li>

              
              <li class="nav-item">
                <a href="#staffSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link"  >
                  <span data-feather="users" class="align-text-bottom"></span>
                  Staff
                </a>
                <ul class="collapse list-unstyled submenu" id="staffSubmenu">
                      <li>
                          <a href="/bit/staff/create.php" class="nav-link" >
                            <span data-feather="user-plus" class="align-text-bottom"></span>
                              Add Staff
                          </a>
                      </li>
                      <li>
                          <a href="/bit/staff/index.php" class="nav-link">
                            <span data-feather="grid" class="align-text-bottom"></span>
                              Manage Staff
                          </a>
                      </li>
                </ul>

              </li>
              

              <li class="nav-item">
                      <a href="#categorySubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link"  >
                          <span data-feather="layers" class="align-text-bottom" ></span>
                          Category
                      </a>
                    <ul class="collapse list-unstyled submenu" id="categorySubmenu">
                        <li>
                            <a href="/bit/category/create.php" class="nav-link" >
                              <span data-feather="plus-circle" class="align-text-bottom"></span>
                                Add Category
                            </a>
                        </li>

                        <li>
                            <a href="/bit/Parent Category add/create.php" class="nav-link" >
                              <span data-feather="plus-circle" class="align-text-bottom"></span>
                                Add Parent Category
                            </a>
                        </li>
                        <li>
                            <a href="/bit/category/index.php" class="nav-link">
                              <span data-feather="grid" class="align-text-bottom"></span>
                                Manage Category
                            </a>
                        </li>
                    </ul>

              </li>

              <li class="nav-item">
                  <a href="#productsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link"  >
                      <span data-feather="shopping-cart" class="align-text-bottom"></span>
                      Products
                  </a>
                  <ul class="collapse list-unstyled submenu" id="productsSubmenu">
                        <li>
                            <a href="/bit/product/create.php" class="nav-link" >
                              <span data-feather="plus-circle" class="align-text-bottom"></span>
                                Add Products
                            </a>
                        </li>
                        <li>
                            <a href="/bit/Product/index.php" class="nav-link">
                              <span data-feather="grid" class="align-text-bottom"></span>
                                Manage Products
                            </a>
                        </li>
                        
                  </ul>
              </li>

                
              <li class="nav-item">
                  <a href="#suppliersSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link"  >
                        <span data-feather="users" class="align-text-bottom"></span>
                        Suppliers
                  </a>

                  <ul class="collapse list-unstyled submenu" id="suppliersSubmenu">
                        <li>
                            <a href="/bit/suppliers/create.php" class="nav-link" >
                              <span data-feather="user-plus" class="align-text-bottom"></span>
                                Add Suppliers
                            </a>
                        </li>
                        <li>
                            <a href="/bit/suppliers/index.php" class="nav-link">
                              <span data-feather="grid" class="align-text-bottom"></span> 
                                Manage Suppliers
                            </a>
                        </li>
                  </ul>
              </li>


              <li class="nav-item">
                  <a href="#customersSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link"  >
                        <span data-feather="users" class="align-text-bottom"></span>
                        Customers
                  </a>
                  <ul class="collapse list-unstyled submenu" id="customersSubmenu">
                        <li>
                            <a href="/bit/customers/create.php" class="nav-link" >
                              <span data-feather="user-plus" class="align-text-bottom"></span>
                              Add Customers
                            </a>
                        </li>
                        <li>
                            <a href="/bit/customers/index.php" class="nav-link">
                              <span data-feather="grid" class="align-text-bottom"></span> 
                              Manage Customers
                            </a>
                        </li>
                  </ul>
              </li>

              

              <li class="nav-item">
                <a href="#purchasesSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link"  >
                    <span data-feather="shopping-bag" class="align-text-bottom"></span>
                      Purchase Orders
                </a>
                  <ul class="collapse list-unstyled submenu" id="purchasesSubmenu">
                        <li>
                            <a href="/bit/purchases/create.php" class="nav-link" >
                              <span data-feather="plus-circle" class="align-text-bottom"></span>
                              Add Purchase Order
                            </a>
                        </li>
                        <li>
                            <a href="/bit/purchases/index.php" class="nav-link">
                              <span data-feather="grid" class="align-text-bottom"></span>
                              Manage Purchase Orders
                            </a>
                        </li>
                  </ul>
              </li>
              

              <li class="nav-item">
                  <a href="#grnSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link"  >
                      <span data-feather="check-circle" class="align-text-bottom"></span>
                    GRN
                  </a>
                    <ul class="collapse list-unstyled submenu" id="grnSubmenu">
                          <li>
                              <a href="/bit/grn/create.php" class="nav-link" >
                                <span data-feather="plus-circle" class="align-text-bottom"></span>
                                  Add GRN
                              </a>
                          </li>
                          <li>
                              <a href="/bit/grn/index.php" class="nav-link">
                                <span data-feather="grid" class="align-text-bottom"></span>
                                Manage GRN
                              </a>
                          </li>
                    </ul>
              </li>

              <li class="nav-item">
                  <a href="#purchasereturnSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link"  >
                    <span data-feather="corner-down-left" class="align-text-bottom"></span>
                    Purchase Return
                  </a>
                      <ul class="collapse list-unstyled submenu" id="purchasereturnSubmenu">
                          <li>
                              <a href="/bit/purchase_returns/create.php" class="nav-link" >
                                <span data-feather="plus-circle" class="align-text-bottom"></span>
                                  Add Purchase Return
                              </a>
                          </li>
                          <li>
                              <a href="/bit/purchase_returns/index.php" class="nav-link">
                                <span data-feather="grid" class="align-text-bottom"></span>
                                  Manage Purchase Return
                              </a>
                          </li>
                      </ul>
              </li>

              <li class="nav-item">
                <a href="#invoicesSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link"  >
                  <span data-feather="file-text" class="align-text-bottom"></span>
                  Sales Invoices
                </a>
                  <ul class="collapse list-unstyled submenu" id="invoicesSubmenu">
                        <li>
                            <a href="/bit/invoices/create.php" class="nav-link" >
                              <span data-feather="plus-circle" class="align-text-bottom"></span>
                                Add Sales Invoices
                            </a>
                        </li>
                        <li>
                            <a href="/bit/invoices/index.php" class="nav-link">
                              <span data-feather="grid" class="align-text-bottom"></span>
                                Manage Sales Invoices
                            </a>
                        </li>
                  </ul>

              </li>

              <li class="nav-item">
                <a href="#salesreturnSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link"  >
                  <span data-feather="corner-down-left" class="align-text-bottom"></span>
                  Sales Return
                </a>
                <ul class="collapse list-unstyled submenu" id="salesreturnSubmenu">
                      <li>
                          <a href="/bit/sale_return/create.php" class="nav-link" >
                          <span data-feather="plus-circle" class="align-text-bottom"></span>
                            Add Sales Return
                          </a>
                      </li>
                      <li>
                          <a href="/bit/sale_return/index.php" class="nav-link">
                            <span data-feather="grid" class="align-text-bottom"></span>
                              Manage Sales Return
                          </a>
                      </li>
                </ul>

              </li>

              <li class="nav-item">
                  <a class="nav-link" href="/bit/stock/index.php">
                      <span data-feather="layers" class="align-text-bottom"></span>
                      Stock
                  </a>
              </li>
              
              <li class="nav-item">
                  <a class="nav-link" href="/bit/reports/index.php">
                      <span data-feather="bar-chart-2" class="align-text-bottom"></span>
                      Reports
                  </a>
              </li>
              
              <!--<li class="nav-item">
                  <a class="nav-link" href="https://calendar.google.com/calendar" target="_blank" style="color: blue;">
                      <span data-feather="calendar" class="align-text-bottom"></span>
                      Calendar
                  </a>
              </li>
              -->
            </ul>
      </div>
    </nav>
  </div>
</div>

        


      <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>

      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
      <script src="/bit/common/dashboard.js"></script>
      <!-- jQuery CDN - Slim version (=without AJAX) -->
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
      <!-- Popper.JS -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
      <!-- Bootstrap JS -->
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
      <!-- jQuery Custom Scroller CDN -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

        
      