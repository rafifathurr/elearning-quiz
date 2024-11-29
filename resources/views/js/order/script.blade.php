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
        $('#dt-order').DataTable({
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
                    data: 'name',
                    defaultContent: '-'
                },
                {
                    data: 'total_price',
                    defaultContent: '-'
                },
                {
                    data: 'class',
                    defaultContent: '-'
                },
                {
                    data: 'payment_method',
                    defaultContent: '-'
                },
                {
                    data: 'status',
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

    function dataTableAdmin() {
        const url = $('#url_dt').val();
        $('#dt-order').DataTable({
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
                    data: 'name',
                    defaultContent: '-'
                },
                {
                    data: 'total_price',
                    defaultContent: '-'
                },
                {
                    data: 'class',
                    defaultContent: '-'
                },
                {
                    data: 'payment_method',
                    defaultContent: '-'
                },
                {
                    data: 'attachment',
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

    function checkOut(id, name) {
        console.log('Checkout ID:', id); // Debugging
        let token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: `Checkout Paket: ${name}`,
            input: 'select',
            inputOptions: {
                non_tunai: "Non Tunai",
                tunai: "Tunai",
            },
            inputPlaceholder: "Pilih Metode Pembayaran",
            showCancelButton: true,
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-primary mr-2 mb-3',
                cancelButton: 'btn btn-danger mb-3',
            },
            buttonsStyling: false,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
            preConfirm: (paymentMethod) => {
                if (!paymentMethod) {
                    Swal.showValidationMessage('Harap pilih metode pembayaran');
                }
                return paymentMethod;
            },
        }).then(result => {
            if (result.isConfirmed) {
                console.log('Metode Pembayaran:', result.value); // Debugging
                swalProcess();
                $.ajax({
                    url: '{{ url('order/checkout') }}/' + id,
                    type: 'POST',
                    cache: false,
                    data: {
                        _token: token,
                        payment_method: result.value,
                    },
                    success: function(data) {
                        console.log('Success Response:', data); // Debugging
                        location.reload();
                    },
                    error: function(xhr, error, code) {
                        console.log('Error:', xhr, error, code); // Debugging
                        swalError(error);
                    }
                });
            }
        });
    }

    function cancelOrder(id) {
        let token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: 'Apakah Anda Yakin Ingin Membatalkan Order ?',
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
                    url: '{{ url('order') }}/' + id,
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

    function payOrder(id, name) {
        console.log('Order ID:', id); // Debugging
        let token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: `Bukti Pembayaran: ${name}`,
            html: `
            <input type="file" id="uploadImage" class="swal2-file-input" accept="image/*" aria-label="Upload Bukti Pembayaran" style="display: block; margin: 0 auto; width: 100%; padding: 10px ;border: 1px solid #ddd; border-radius: 5px; font-size: 16px; ">`,
            showCancelButton: true,
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-primary mr-2 mb-3',
                cancelButton: 'btn btn-danger mb-3',
            },
            buttonsStyling: false,
            confirmButtonText: 'Upload',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const uploadImage = document.getElementById('uploadImage').files[0];
                if (!uploadImage) {
                    Swal.showValidationMessage('Harap Upload Bukti Pembayaran');
                    return false;
                }
                return uploadImage; // Return the file object
            },
        }).then(result => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('upload_image', result.value);
                formData.append('_token', token);

                swalProcess();
                $.ajax({
                    url: `/order/payment/${id}`,
                    type: 'POST',
                    processData: false, // Important for FormData
                    contentType: false, // Important for FormData
                    data: formData,
                    success: function(data) {
                        console.log('Success Response:', data); // Debugging
                        location.reload();
                    },
                    error: function(xhr, error, code) {
                        console.log('Error:', xhr, error, code); // Debugging
                        swalError(error);
                    }
                });
            }
        });
    }

    function approveOrder(id) {
        let token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: 'Apakah Anda Yakin Ingin Menerima Order Ini?',
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
                    url: `/order/approve/${id}`,
                    type: 'POST',
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

    function rejectOrder(id) {
        let token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: 'Apakah Anda Yakin Ingin Menolak Order Ini?',
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
                    url: `/order/reject/${id}`,
                    type: 'POST',
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
</script>
