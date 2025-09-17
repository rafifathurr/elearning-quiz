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
                    d.package = $('#packageFilter').val();
                    d.dateClass = $('#dateClassFilter').val();
                    let reservation = $('#reservation').val(); // contoh: "2025-09-01 - 2025-09-17"
                    if (reservation) {
                        let dates = reservation.split(' - ');
                        d.startDate = dates[0];
                        d.endDate = dates[1];
                    }
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
                {
                    data: 'action',
                    width: '20%',
                    defaultContent: '-',
                    orderable: false,
                    searchable: false
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

        // Event listener untuk filter StartDate
        $('#startDate').on('change', function() {
            table.ajax.reload();
        });

        // Event listener untuk filter End Date
        $('#endDate').on('change', function() {
            table.ajax.reload();
        });
    }

    $(function() {
        // Dapatkan tanggal hari ini
        let today = moment().endOf('day');

        // Date range picker
        $('#reservation').daterangepicker({
            autoUpdateInput: false, // supaya kosong saat load
            maxDate: today, // batas maksimal hari ini
            locale: {
                format: 'YYYY-MM-DD',
                cancelLabel: 'Clear' // tombol clear
            }
        });

        // Apply (ketika pilih range)
        $('#reservation').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                'YYYY-MM-DD'));
            $('#dt-member-package').DataTable().ajax.reload();
        });

        // Cancel (ketika clear)
        $('#reservation').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            $('#dt-member-package').DataTable().ajax.reload();
        });
    });

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
