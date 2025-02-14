<script type="text/javascript">
    function dataTableAdmin() {
        const url = $('#url_dt').val();
        $('#dt-history-test').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                error: function(xhr, error, code) {
                    swalError(xhr.statusText);
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    width: '5%',
                    searchable: false
                },
                {
                    data: 'package',
                    defaultContent: '-'
                },
                {
                    data: 'name',
                    defaultContent: '-'
                },
                {
                    data: 'quiz',
                    defaultContent: '-'
                },
                {
                    data: 'type_quiz',
                    defaultContent: '-'
                },
                {
                    data: 'action',
                    width: '20%',
                    defaultContent: '-',
                    orderable: false,
                    searchable: false
                },
            ]

        });

    }

    function dataTable() {
        const url = $('#url_dt').val();
        $('#dt-mytest').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                error: function(xhr, error, code) {
                    swalError(xhr.statusText);
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    width: '5%',
                    searchable: false
                },
                {
                    data: 'package',
                    defaultContent: '-'
                },
                {
                    data: 'quiz',
                    defaultContent: '-'
                },
                {
                    data: 'type_quiz',
                    defaultContent: '-'
                },
                {
                    data: 'action',
                    width: '20%',
                    defaultContent: '-',
                    orderable: false,
                    searchable: false
                },
            ]

        });

    }

    function destroyRecord(id) {
        let token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: 'Apakah Anda Yakin Ingin Menghapus Data Ini?',
            icon: 'question',
            showCancelButton: true,
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-primary mr-2 mb-3',
                cancelButton: 'btn btn-danger mb-3',
            },
            buttonsStyling: false,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                swalProcess();
                $.ajax({
                    url: '{{ url('mytest') }}/' + id,
                    type: 'DELETE',
                    cache: false,
                    data: {
                        _token: token
                    },
                    success: function(data) {
                        location.reload();
                    },
                    error: function(xhr, error, code) {
                        swalError(error);
                    }
                });
            }
        })
    }

    $(document).on('click', '.btn-lanjutkan', function(e) {
        e.preventDefault(); // Mencegah navigasi default sementara
        localStorage.removeItem('remainingTime'); // Hapus data dari local storage

        // Arahkan ke URL dari tombol "Lanjutkan"
        const url = $(this).attr('href');
        window.location.href = url;
    });
</script>
