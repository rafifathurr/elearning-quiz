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
                swalProcess();
                $('form').unbind('submit').submit()
            }
        });
    });

    function dataTable() {
        const url = $('#url_dt').val();
        $('#dt-voucher').DataTable({
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
                    data: 'package_name',
                    defaultContent: '-'
                },
                {
                    data: 'name',
                    defaultContent: '-'
                },
                {
                    data: 'discount',
                    defaultContent: '-'
                },
                {
                    data: 'fixed_price',
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
        }).then(result => {
            if (result.isConfirmed) {
                swalProcess();
                $.ajax({
                    url: '{{ url('master/voucher') }}/' + id,
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
        });
    }

    function checkOutVoucher(id, name, harga) {
        let token = $('meta[name="csrf-token"]').attr('content');

        // Pop-up: Pilih jumlah voucher
        Swal.fire({
            title: `Ambil Voucher: ${name}`,
            html: `
            <p>Masukan jumlah voucher yang anda inginkan</p>
            <div style="display:flex; align-items:center; justify-content:center; gap: 5px;">
                <button type="button" id="btn-minus" class="btn btn-secondary" style="width:45px; height:45px; font-size:20px;">−</button>
                <input type="number" id="jumlah-voucher" class="swal2-input" 
                    style="width:80px; height:45px; text-align:center; font-size:18px; margin:0;" min="1" value="1">
                <button type="button" id="btn-plus" class="btn btn-secondary" style="width:45px; height:45px; font-size:20px;">+</button>
            </div>
        `,
            icon: 'question',
            showCancelButton: true,
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-primary mr-2 mb-3',
                cancelButton: 'btn btn-danger mb-3',
            },
            buttonsStyling: false,
            confirmButtonText: 'Lanjutkan',
            cancelButtonText: 'Batal',
            didOpen: () => {
                const input = document.getElementById('jumlah-voucher');
                document.getElementById('btn-plus').addEventListener('click', () => {
                    input.value = parseInt(input.value || 1) + 1;
                });
                document.getElementById('btn-minus').addEventListener('click', () => {
                    if (parseInt(input.value) > 1) {
                        input.value = parseInt(input.value) - 1;
                    }
                });
            },
            preConfirm: () => {
                const jumlah = document.getElementById('jumlah-voucher').value;
                if (!jumlah || jumlah < 1) {
                    Swal.showValidationMessage('Masukkan jumlah yang valid');
                }
                return jumlah;
            }
        }).then(result => {
            if (result.isConfirmed) {
                const jumlah = result.value;
                kirimDataCheckout(id, jumlah, harga, 'transfer');
            }
        });
    }

    function kirimDataCheckout(id, jumlah, harga, metode) {
        let token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: '{{ route('order.checkOutVoucher', ':id') }}'.replace(':id', id),
            type: 'POST',
            data: {
                _token: token,
                voucher_id: id,
                jumlah: jumlah,
                metode: metode,
                harga: harga
            },
            success: function(data) {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    location.reload();
                }
            },
            error: function(xhr, error, code) {
                console.log('Error:', xhr, error, code);
                swalError(error);
            }
        });
    }
</script>
