<script type="text/javascript">
    function dataTable() {
        const url = $('#url_dt').val();

        let table = $('#dt-member-package').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
                data: function(d) {
                    d.package = $('#packageFilter').val(); // Filter paket
                    d.dateClass = $('#dateClassFilter').val(); // Filter tanggal kelas
                },
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
                    data: 'created_at',
                    defaultContent: '-'
                },
                {
                    data: 'user',
                    defaultContent: '-'
                },
                {
                    data: 'email',
                    defaultContent: '-'
                },
                {
                    data: 'phone',
                    defaultContent: '-'
                },
                {
                    data: 'date',
                    defaultContent: '-'
                },
            ]
        });

        // Event listener untuk filter paket
        $('#packageFilter').on('change', function() {
            table.ajax.reload();
        });

        // Event listener untuk filter tanggal kelas
        $('#dateClassFilter').on('change', function() {
            table.ajax.reload();
        });
    }

    $('#packageFilter').on('change', function() {
        console.log('Paket berubah');
        const dateClass = $(this).val();
        const dateDropdown = $('#dateClassFilter');

        // Kosongkan dropdown aspek
        dateDropdown.empty();
        dateDropdown.append('<option value="">-- Semua Jadwal Kelas --</option>');

        if (dateClass) {
            // Panggil API untuk mendapatkan data aspek berdasarkan type_aspect
            $.ajax({
                url: '{{ url('master/member/get-date') }}',
                type: 'GET',
                data: {
                    package_id: dateClass
                },
                success: function(data) {
                    // Tambahkan data ke dropdown aspek
                    data.forEach(function(date) {
                        dateDropdown.append(
                            `<option value="${date.id}">${date.name}</option>`
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
