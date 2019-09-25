<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <base href="{{ asset("") }}">

    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">         --}}
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/main.css">
    @stack('style')
    

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="js/all.js"></script>
    <script src="js/main.js"></script>
    @stack('script')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
</head>

<body>
    <div class="pt-5" id="app">
        @guest
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'eWallet') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            @yield('content')
        </main>
        @endguest

        @auth
        @if (auth()->user()->hasVerifiedEmail())
            @include('layouts.nav')
            <div class="page-content p-5 fixed-top" id="content">
                <!-- Toggle button -->
                <button id="sidebarCollapse" type="button" class="btn btn-light bg-white rounded-pill shadow-sm px-4 mb-4"><i
                        class="fa fa-bars mr-2"></i><small class="text-uppercase font-weight-bold">Menu</small></button>
            </div>
            <div class="row page-content p-5 fixed-bottom justify-content-center" id="content" 
                @if (!in_array(Route::currentRouteName(), ['home', 'wallet.show', 'cat.show', 'cat.index'])) 
                    style="display: none" 
                @endif
            >      
                <a href="
                @if (Route::currentRouteName() == 'cat.index') 
                    cat/create 
                @else 
                    transaction/create
                @endif " id="add" class="btn btn-primary btn-circle" data-toggle="tooltip" data-placement="top" title="Add new 
                @if (Route::currentRouteName() == 'cat.index')
                    category
                @else
                    transaction
                @endif "><i class="fas fa-plus"></i></a>
            </div>
            <div class="page-content p-5" id="content">
                @yield('content')
            </div>
        @else
            <div class="page-content p-5 active" id="content">
                @yield('content')
            </div>
        @endif
            
        @endauth
    </div>
</body>

</html>
