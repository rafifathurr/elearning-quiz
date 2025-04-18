@extends('layouts.main')
@section('section')

    <body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">
        <div class="wrapper">

            <!-- Preloader -->
            {{-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo" height="60"
                width="60">
        </div> --}}

            <!-- Navbar -->
            @include('layouts.navbar')
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            @include('layouts.sidebar')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">

                @yield('content')

            </div>
            <!-- /.content-wrapper -->
            @include('layouts.footer')

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

        <!-- FireBase -->
        @auth
            <script type="module">
                // Import the functions you need from the SDKs you need
                import {
                    initializeApp
                } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-app.js";
                import {
                    getAnalytics
                } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-analytics.js";
                import {
                    getMessaging
                } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-messaging.js";

                // TODO: Add SDKs for Firebase products that you want to use
                // https://firebase.google.com/docs/web/setup#available-libraries

                // Your web app's Firebase configuration
                // For Firebase JS SDK v7.20.0 and later, measurementId is optional
                const firebaseConfig = {
                    apiKey: "AIzaSyAxX4jUdibL9n8rJqEeEnxqPKu3S1uAQ9k",
                    authDomain: "brata-cerdas-1.firebaseapp.com",
                    projectId: "brata-cerdas-1",
                    storageBucket: "brata-cerdas-1.firebasestorage.app",
                    messagingSenderId: "319745808387",
                    appId: "1:319745808387:web:de0c26d961571816af1636",
                    measurementId: "G-XE79X5SH5R"
                };
            </script>
        @endauth
    </body>
@endsection
