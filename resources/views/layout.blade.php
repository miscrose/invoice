<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        .toggle-button {
            position: relative;
            width: 60px;
            height: 20px;
            background-color: red;
            border-radius: 20px;
            border: 2px solid black;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .toggle-button .toggle-circle {
            position: absolute;
            width: 17px;
            height: 17px;
            background-color: black;
            border-radius: 50%;
           
        }
        .toggle-button.on {
            background-color: green;
        }
        .toggle-button.on .toggle-circle {
            transform: translateX(40px);
        }
      </style>
    <!-- Custom fonts for this template-->
   <!-- <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">  -->
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('home')}}">
                <div class="sidebar-brand-icon rotate-n-15">
                   ........ 
                </div>
                <div class="sidebar-brand-text mx-3"> </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{route('dashboard')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            @if (Auth::user()->usertype=='admin')
            <li class="nav-item active">
                <a class="nav-link" href="{{route('validation_list')}}">
                   
                    <span>Validation list</span></a>
            </li>
          

            <li class="nav-item active">
                <a class="nav-link" href="{{route('admin_list_acc')}}">
                   
                    <span>admin</span></a>
            </li>

         

         
            @endif
            @if (Auth::user()->usertype=='user')
            <li class="nav-item active">
                <a class="nav-link" href="{{route('home')}}">
                   
                    <span>My invoices</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="{{route('quote_list')}}">
                   
                    <span>My quotes</span></a>
            </li>
            @endif
           
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

         

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    @yield('search')

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        @if (Auth::user()->usertype=='admin')
              
                          <form action="{{route('invoice_form')}}" method="POST" class="mx-2 mt-3">
                            @csrf
                            <button class="btn btn-info btn-icon-split " type="submit">Add Invoice</button>
                        </form>
                       
                        <form action="{{ route('devis_form') }}" method="POST" class="mx-2 mt-3">
                            @csrf
                            <button class="btn btn-info btn-icon-split" type="submit">Add Quote</button>
                        </form>
                        
                        
                        @else
                        
                        
                 
                            <form action="{{ route('invoice_form_client') }}" method="POST" class="mx-2 mt-3">
                                @csrf
                                <button class="btn btn-info btn-icon-split " type="submit">Add Invoice</button>
                            </form>
                        
                            <form action="{{ route('devis_form_client') }}" method="POST" class="mx-2 mt-3">
                                @csrf
                                <button class="btn btn-info btn-icon-split" type="submit">Add Quote</button>
                            </form>
                   

                        
                        @endif
                        
            
            
            
            
                          
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">   {{Auth::user()->name}}</span>
                                <i class="fa-solid fa-angle-down"></i>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                
                                
                                <a class="dropdown-item" href="{{ route('setting') }}">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                              
                                @if (Auth::user()->usertype=='user')
                               
                                  
                                  
                                   <form action="{{route('information')}}" method="POST">
                                    @csrf
                                    
                                    <button class="dropdown-item" type="submit"><i class="fa-regular  fa-note-sticky fa-fw mr-2 text-gray-400"></i>information </button>
                                  </form>
                                 
                                
                                @endif

     
                              
                                @if (Auth::user()->usertype=='admin')

                                <form action="{{ route('company_info') }}" method="POST" >
                                    @csrf
                                   
                                    <button class="dropdown-item" type="submit">  <i class="fa-regular  fa-note-sticky fa-fw mr-2 text-gray-400"></i>Company Info</button>
                                </form>
                                @endif



                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                @if (Auth::user()->uservalid==='v')
                <div class="container-fluid " >
                
                    @yield('contenu')
                
                </div>
                
                @else
                    <h1>attente de validation du compte</h1>
                @endif
                
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

      

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                   
                    <form action="{{route('logout')}}" method="POST">
                        @csrf
                        <button class="btn btn-primary" type="submit">logout</button>
                      </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('script')
</body>

</html>