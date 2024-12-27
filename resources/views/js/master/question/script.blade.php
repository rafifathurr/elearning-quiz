<script type="text/javascript">
    $('form').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda Ingin Menyimpan Data Ini?',
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
                swalProcess()
                $('form').unbind('submit').submit()
            }
        });
    });

    function dataTable() {
        const url = $('#url_dt').val();
        const table = $('#dt-question').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                data: function(d) {
                    d.aspect = $('#filter-aspect').val();
                    d.type_aspect = $('#filter-type_aspect').val();
                },
                error: function(xhr, error, code) {
                    swalError(xhr.statusText);
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    width: '5%',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'question',
                    defaultContent: '-',
                    orderable: false
                },
                {
                    data: 'aspect',
                    defaultContent: '-',
                    orderable: false
                },
                {
                    data: 'description',
                    defaultContent: '-',
                    orderable: false
                },
                {
                    data: 'level',
                    defaultContent: '-',
                    orderable: false
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
        $('#filter-aspect').on('change', function() {
            table.draw();
        });
        $('#filter-type_aspect').on('change', function() {
            table.draw();
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
                    url: '{{ url('master/question') }}/' + id,
                    type: 'delete',
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
        });
    }

    $('#filter-type_aspect').on('change', function() {
        console.log('type aspect berubah');
        const typeAspect = $(this).val();
        const aspectDropdown = $('#filter-aspect');

        // Kosongkan dropdown aspek
        aspectDropdown.empty();
        aspectDropdown.append('<option value="">-- Pilih Aspek --</option>');

        if (typeAspect) {
            // Panggil API untuk mendapatkan data aspek berdasarkan type_aspect
            $.ajax({
                url: '{{ url('master/aspect/get-aspect') }}',
                type: 'GET',
                data: {
                    type_aspect: typeAspect
                },
                success: function(data) {
                    // Tambahkan data ke dropdown aspek
                    data.forEach(function(aspect) {
                        aspectDropdown.append(
                            `<option value="${aspect.id}">${aspect.name}</option>`
                        );
                    });
                },
                error: function() {
                    console.error('Gagal memuat data aspek');
                }
            });
        }
    });
</script>
