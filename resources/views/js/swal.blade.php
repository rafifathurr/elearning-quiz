@if (Session::has('success'))
    <script type="text/javascript">
        Swal.fire({
            icon: 'success',
            title: '{{ Session::get('success') }}',
            customClass: {
                confirmButton: 'btn btn-primary mb-3',
            },
            buttonsStyling: false,
            timer: 3000,
        });
    </script>
    @php
        Session::forget('success');
    @endphp
@elseif(Session::has('failed'))
    <script type="text/javascript">
        Swal.fire({
            icon: 'warning',
            title: '{{ Session::get('failed') }}',
            customClass: {
                confirmButton: 'btn btn-primary mb-3',
            },
            buttonsStyling: false,
            timer: 3000,
        });
    </script>
    @php
        Session::forget('failed');
    @endphp
@endif
<script>
    function swalProcess() {
        Swal.fire({
            title: 'Mohon menunggu',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });
    }

    function swalError(messages) {
        Swal.fire({
            icon: 'warning',
            title: messages,
            customClass: {
                confirmButton: 'btn btn-primary mb-3',
            },
            buttonsStyling: false,
            timer: 3000,
        });
    }
</script>
