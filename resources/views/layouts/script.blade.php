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

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
        const messaging = getMessaging(app);
        getToken(messaging, {
            vapidKey: "BKCYJjmPhEQ9LKpeyxSy7Ui1FhhGcC5Rz6W6L08he9rr6ZEShmx_U8d9HcIC7qzbzM-Hwl-uQzgnY24ij18U-xs"
        });
    </script>
@endauth

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/adminlte/js/adminlte.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('dist/adminlte/js/pages/dashboard.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>


@include('js.swal')
@stack('javascript-bottom')
{{-- @include('sweetalert::alert') --}}
<script>
    $(document).ready(function() {
        // Aturan khusus untuk elemen dengan kelas 'question.summernote'
        $('.question.summernote').summernote({
            height: 150,
            airMode: false,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ],
            callbacks: {
                onImageUpload: function() {
                    alert('Upload gambar tidak diperbolehkan!');
                    return false;
                }
            }
        });

        // Aturan default untuk elemen lain dengan kelas 'summernote'
        $('.summernote').not('.question').summernote({
            height: 150
        });
    });
</script>
