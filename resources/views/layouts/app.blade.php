<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @routes
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--    <meta name="jwt-token" content="{{ session('token') }}">--}}

    <title>{{config('app.name')}} - @yield('title', config('app.name'))</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.min.css" rel="stylesheet">



<!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
<v-app id="app" data-app="true">

    @section('header')
        @yield('notification')
        <ui-header :user="{{ json_encode( Auth::user()) ?? ""}}" role="{{\App\Services\UserService::getAuthRoles()}}"></ui-header>
    @show
    <v-container class="main_content">
        <main id="main_page" style="margin-bottom: 20px">
            @yield('content')
        </main>
    </v-container>
    @section('footer')
        <ui-footer></ui-footer>
    @show
</v-app>
@section('scripts')
    <!-- Scripts -->
    <script src="{{ mix('/js/app.js') }}" defer></script>

@show

</body>
</html>
