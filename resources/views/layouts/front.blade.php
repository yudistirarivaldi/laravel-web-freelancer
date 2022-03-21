<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

        @include('includes.landing.meta')

        <title>@yield('title') | SERV</title>

        @stack('before-style')

        @include('includes.landing.style')

        @stack('after-style')

    </head>
    <body class="antialised">
        <div class="relative">

            @include('includes.landing.header')

                {{-- @include('sweetalert::alert') --}}

                @yield('content')


            @include('includes.landing.footer')

            @stack('script-style')

            @include('includes.landing.script')

            @stack('after-script')

            {{-- Modals --}}
            @include('components.Modal.login')
            @include('components.Modal.register')
            @include('components.Modal.register-success')

        </div>
    </body>
</html>
