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
@elseif (Session::has('berhasil'))
    <script type="text/javascript">
        Swal.fire({
            icon: 'success',
            title: '{{ Session::get('berhasil') }}',
            customClass: {
                confirmButton: 'btn btn-primary mb-3',
            },
            confirmButtonText: "Lihat Paket",
            buttonsStyling: false,
            showCloseButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('order.index') }}";
            }
        });
    </script>
    @php
        Session::forget('berhasil');
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
