<script type="text/javascript">
    function dataTable() {
        const url = $('#url_dt').val();
        $('#dt-myvoucher').DataTable({
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
                    data: 'voucher_code',
                    defaultContent: '-'
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
                    data: 'type_voucher',
                    defaultContent: '-'
                },
                {
                    data: 'price',
                    defaultContent: '-'
                },

                {
                    data: 'status',
                    defaultContent: '-'
                },

            ]

        });

    }
</script>
