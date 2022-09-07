<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Cashbook</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v.2">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatable.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/js/core.js') }}"></script>
    <link rel="stylesheet" href="{{asset('assets/css/jquery-confirm.min.css')}}" type="text/css">

    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
          new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages : 'ur,en'}, 'google_translate_element');
        }
    </script>
</head>
<body>
    <div id="app">
        <aside class="theme-aside minimized">
            <div class="top">
                <a href="{{ url('/dashboard') }}" class="text-decoration-none">
                    {{-- <img src="{{ asset('assets/images/logo.png') }}" alt="" class="logo"> --}}
                    <h1 class="logo m-0 d-none">Cashbook</h1>
                </a>
                <button class="btn-none p-0 aside-toggle">
                    <img src="{{ asset('assets/images/svg/menu.svg') }}" alt="menu" width="22px">
                </button>
            </div>
            <ul class="menu">
                <li title="Dashboard">
                    <a href="{{ url('/dashboard') }}" <?php if(request()->is('dashboard')){ echo "class='active'";} ?> >
                        <img src="{{ asset('assets/images/svg/dashboard.svg') }}" alt="">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li title="Profit Calculation">
                    <a href="{{ url('/profit-calculation') }}" <?php if(request()->is('profit-calculation')){ echo "class='active'";} ?> class="">
                        <img src="{{ asset('assets/images/svg/profit.svg') }}" alt="">
                        <span>Profit Calculation</span>
                    </a>
                </li>
                <li title="Transactions">
                    <a href="{{ route('transaction.index') }}" <?php if(request()->is('transactions*')){ echo "class='active'";} ?>>
                        <img src="{{ asset('assets/images/svg/transactions.svg') }}" alt="">
                        <span>Transactions</span>
                    </a>
                </li>
                <li title="Reports">
                    <a href="{{ route('reports.index') }}" <?php if(request()->is('reports*')){ echo "class='active'";} ?>>
                        <img src="{{ asset('assets/images/svg/reports.svg') }}" alt="">
                        <span>Reports</span>
                    </a>
                </li>
                @canany(['user-view'])

                <li title="Users">
                    <a href="{{ route('users.index') }}" <?php if(request()->is('users*')){ echo "class='active'";} ?>>
                        <img src="{{ asset('assets/images/svg/users.svg') }}" alt="">
                        <span>Users</span>
                    </a>
                </li>
                @endcan
                <li title="Customers">
                    <a href="{{ route('customers.index') }}" <?php if(request()->is('customers*')){ echo "class='active'";} ?>>
                        <img src="{{ asset('assets/images/svg/users.svg') }}" alt="">
                        <span>Customers</span>
                    </a>
                </li>
                @canany(['delete-view-transactions'])
                <li title="Activity Log">
                    <a href="{{ route('activity-log.index') }}" <?php if(request()->is('activity-log*')){ echo "class='active'";} ?>>
                        <img src="{{ asset('assets/images/svg/activity-log.svg') }}" alt="">
                        <span>Activity Log</span>
                    </a>
                </li>
                @endcan
                @canany(['delete-view-transactions'])
                <li title="Delete Transaction">
                    <a href="{{ route('delete.transaction') }}" <?php if(request()->is('delete-transactions*')){ echo "class='active'";} ?>>
                        <img src="{{ asset('assets/images/svg/activity-log.svg') }}" alt="">
                        <span>Delete Transaction</span>
                    </a>
                </li>
                @endcan
            </ul>
        </aside>

        <main class="main active">

            <nav class="theme-navbar">
                <div class="d-flex align-items-center">
                    {{-- <h1 class="mb-0"></h1> --}}
                    <button class="btn-none p-0 aside-toggle d-xl-none me-3">
                        <img src="{{ asset('assets/images/svg/menu2.svg') }}" alt="menu" width="22px">
                    </button>
                    <div id="google_translate_element"></div>
                </div>

                <div class="profile-info" id="profile-toggle">
                    <img src="{{ asset('assets/images/avatar.png') }}" alt="">
                    <div>
                        <h6>{{Auth::user()->name;}}</h6>
                        <p>{{Auth::user()->roles->pluck('name')[0] }}</p>
                    </div>
                    <img src="{{ asset('assets/images/svg/arrow-down.svg') }}" alt="" class="arrow-down">

                    <div id="profile-menu">
                        <a href="{{route('logout')}}" class="text-decoration-none" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">>
                          Logout
                        </a>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </nav>


            @yield('content')
        </main>
    </div>
    @stack('scripts')
    <script src="{{asset('assets/js/jquery-confirm.min.js')}}"></script>

</body>
</html>