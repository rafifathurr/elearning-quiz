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
                    data: 'user',
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
</script>
