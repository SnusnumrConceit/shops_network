<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Shops Network</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" href="">

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .router-link-active {
            color: #fff;
            background: #1d68a7;
        }

        .list-group-item-action:focus, .list-group-item-action:hover {
            color: #fff;
            background: #1d68a7;
        }

        .page-item.active .page-link {
            background: #1d68a7;
            border-color: #1d68a7;
        }
        .fa {
            font-size: 20px;
        }
    </style>
</head>
<body>
<div id="app">
    {{--<div class="flex-center position-ref full-height">--}}
        {{--@if (Route::has('login'))--}}
        {{--<div class="top-right links">--}}
            {{--@auth--}}
            {{--<a href="{{ url('/home') }}">Home</a>--}}
            {{--@else--}}
            {{--<a href="{{ route('login') }}">Login</a>--}}

            {{--@if (Route::has('register'))--}}
            {{--<a href="{{ route('register') }}">Register</a>--}}
            {{--@endif--}}
            {{--@endauth--}}
        {{--</div>--}}
        {{--@endif--}}
    {{--</div>--}}

    <!--<div class="content full-height">
            <div>Авторизован - {{ Auth::user() }}</div>

        <div class="row">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Right Side Of Navbar -->
                <!--<ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
            <!--@guest
                <li class="nav-item">
                    <router-link to="/login" class="nav-link">Войти</router-link>
                        </li>
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/logout"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="/logout" method="POST" style="display: none;">
                                        @csrf
                        </form>
                    </div>
                </li>
                @endguest
                    </ul>
            </div>
            @if (Auth::user())
            <div class="col-md-3 links">
                <div class="list-group">
                    <router-link to="/contracts" class="list-group-item list-group-item-action">Заказы</router-link>
                    <router-link to="/providers" class="list-group-item list-group-item-action">Поставщики</router-link>
                    <router-link to="/shops" class="list-group-item list-group-item-action">Магазины</router-link>
                    <router-link to="/products" class="list-group-item list-group-item-action">Продукция</router-link>
                    <router-link to="/users" class="list-group-item list-group-item-action">Пользователи</router-link>
                </div>
            </div>
            <div class="col-md-9">
                <div class="main">
                    <router-view></router-view>
                </div>
            </div>
            @else
            <div class="col-md-12">
                <router-view></router-view>
            </div>
            @endif
        </div>
    </div>-->
    <app></app>
</div>
<script src="{{ asset('./js/app.js') }}"></script>
</body>
</html>
