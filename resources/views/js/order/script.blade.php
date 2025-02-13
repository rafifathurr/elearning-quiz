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

    $(document).ready(function() {
        $('#table-detail').DataTable({
            "paging": false, // Menonaktifkan pagination
            "info": false, // Menonaktifkan informasi "Showing X of Y entries"
            "searching": false, // Menonaktifkan kolom pencarian
            "ordering": false, // (Opsional) Menonaktifkan fitur sorting
        });
    });



    function dataTable() {
        const url = $('#url_dt').val();
        const table = $('#dt-order').DataTable({
            responsive: false, // Responsiveness dimatikan agar footer terlihat
            autoWidth: false,
            processing: true,
            serverSide: true,
            scrollX: true, // Supaya tidak terpotong saat scroll
            fixedHeader: {
                header: true // Menjaga posisi search tetap fixed
            },
            ajax: {
                url: url,
                dataSrc: function(json) {
                    $('#totalPrice').html(json.totalPrice);
                    const orderId = json.data.length > 0 ? json.data[0].order_id : null;

                    if (orderId) {
                        $('#payButton').show().attr('onclick', `payOrder(${orderId})`);
                    }

                    return json.data;
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
                    data: 'name',
                    defaultContent: '-'
                },
                {
                    data: 'class',
                    defaultContent: '-'
                },
                {
                    data: 'date',
                    defaultContent: '-'
                },
                {
                    data: 'price',
                    defaultContent: '-'
                },
                {
                    data: 'action',
                    width: '20%',
                    defaultContent: '-',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // ✅ Perbaiki header/footer saat resize layar
        $(window).on('resize', function() {
            table.columns.adjust().draw();
            table.fixedHeader.adjust();
        });

        // ✅ Deteksi perubahan ukuran pada DataTables
        $('#dt-order').on('responsive-resize.dt', function() {
            table.columns.adjust().draw();
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
                    data: 'user',
                    defaultContent: '-'
                },
                {
                    data: 'total_price',
                    defaultContent: '-'
                },
                {
                    data: 'payment_method',
                    defaultContent: '-'
                },
                {
                    data: 'payment_date',
                    defaultContent: '-'
                },
                {
                    data: 'proof_payment',
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

    function history() {
        try {
            $('#dt-history').DataTable({
                responsive: true,
                autoWidth: false,
                processing: true,
                serverSide: true,
                ajax: $('#url').val(),
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '5%'
                    },
                    {
                        data: 'order_id'
                    },
                    {
                        data: 'payment_date'
                    },
                    {
                        data: 'status_payment'
                    },
                    {
                        data: 'total_price'
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
        } catch (error) {
            console.error('DataTables Error:', error);
        }
    }


    function checkOut(id, name) {
        let token = $('meta[name="csrf-token"]').attr('content');

        // Ambil jadwal kelas terkait paket
        $.ajax({
            url: '{{ url('order/get-schedule') }}/' + id,
            type: 'GET',
            success: function(response) {
                let scheduleOptions = '';

                if (response.schedules.length > 0) {
                    scheduleOptions =
                        '<select class="form-control mb-3" id="selected_schedule" required>';
                    scheduleOptions += '<option value="" disabled selected>Pilih Jadwal Kelas</option>';

                    response.schedules.forEach(schedule => {
                        scheduleOptions +=
                            `<option value="${schedule.id}">${schedule.name}</option>`;
                    });

                    scheduleOptions += '</select>';
                }

                Swal.fire({
                    title: `Ambil Paket: ${name}`,
                    html: scheduleOptions, // Tampilkan select jadwal jika ada
                    icon: 'question',
                    showCancelButton: true,
                    allowOutsideClick: false,
                    customClass: {
                        confirmButton: 'btn btn-primary mr-2 mb-3',
                        cancelButton: 'btn btn-danger mb-3',
                    },
                    buttonsStyling: false,
                    confirmButtonText: 'Checkout',
                    cancelButtonText: 'Batal',
                    didOpen: () => {
                        // Pastikan dropdown sudah ter-render
                        console.log('Dropdown Loaded:', $('#selected_schedule').length);
                    },
                    preConfirm: () => {
                        const selectedSchedule = $('#selected_schedule').val();
                        console.log('Selected Schedule:',
                            selectedSchedule); // Debugging tambahan
                        if (response.schedules.length > 0 && !selectedSchedule) {
                            Swal.showValidationMessage('Pilih jadwal terlebih dahulu!');
                            return false;
                        }
                        return selectedSchedule ? parseInt(selectedSchedule) : null;
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        console.log('Schedule ID sebelum parse:', result.value);
                        console.log('Schedule ID setelah parse:', (result.value !== undefined &&
                                result.value !== null && result.value !== '0') ?
                            parseInt(result.value) :
                            null);

                        $.ajax({
                            url: '{{ url('order/checkout') }}/' + id,
                            type: 'POST',
                            data: {
                                _token: token,
                                schedule_id: (result.value !== undefined && result.value !==
                                        null && result.value !== '0') ?
                                    parseInt(result.value) : null


                            },
                            success: function(data) {
                                console.log('Success Response:', data);
                                location.reload();
                            },
                            error: function(xhr, error, code) {
                                console.log('Error:', xhr, error, code);
                                swalError(error);
                            }
                        });
                    }
                });
            },
            error: function() {
                Swal.fire('Gagal', 'Tidak dapat memuat jadwal.', 'error');
            }
        });
    }


    function cancelOrder(id) {
        console.log('Paket ID:', id);
        let token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: 'Apakah Anda Yakin Ingin Menghapus Paket ?',
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
                    url: '{{ url('order/delete') }}/' + id,
                    type: 'DELETE',
                    cache: false,
                    data: {
                        _token: token
                    },
                    success: function(data) {
                        location.reload();
                    },
                    error: function(xhr, error, code) {
                        console.log("XHR:", xhr);
                        console.log("Error:", error);
                        console.log("Code:", code);
                        swalError(xhr.responseText); // Tampilkan pesan kesalahan dari server
                    }

                });
            }
        });
    }

    function payOrder(orderId) {
        console.log('Payment Order ID:', orderId);
        let token = $('meta[name="csrf-token"]').attr('content');

        let totalHarga = $('#totalPrice').text();
        let totalHargaNumeric = parseFloat(totalHarga.replace('Rp. ', '').replace(/,/g, '').replace(/\./g, ''));

        Swal.fire({
            title: 'Pembayaran Paket',
            html: `
            <div style="background: #f8f9fa; padding: 10px; border-radius: 8px; font-size: 1.1rem; font-weight: bold;">
                Total Harga: <span style="color: #d9534f;">${totalHarga}</span>
            </div>
            <div style="display: grid; gap: 10px; margin-top: 15px;">
                <button id="btn-transfer" class="swal2-confirm btn-lg" 
                        style="background: #007bff; color: white; padding: 10px; border-radius: 5px; font-size: 1rem;">
                    <i class="fas fa-money-bill-wave"></i> Transfer
                </button>
                <button id="btn-briva" class="swal2-confirm btn-lg" 
                        style="background: #0A3D91; color: white; padding: 10px; border-radius: 5px; font-size: 1rem;" disabled>
                    <i class="fas fa-university"></i> BRIVA
                </button>
            </div>
        `,
            showCancelButton: true,
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {
                cancelButton: 'btn btn-danger btn-lg',
            },
            cancelButtonText: 'Batal',
            didOpen: () => {
                document.getElementById('btn-transfer').addEventListener('click', function() {
                    processPayment('transfer');
                });
                document.getElementById('btn-briva').addEventListener('click', function() {
                    processPayment('briva');
                });
            }
        });

        function processPayment(paymentMethod) {
            swalProcess();
            $.ajax({
                url: '{{ url('order/payment') }}/' + orderId,
                type: 'POST',
                cache: false,
                data: {
                    _token: token,
                    payment_method: paymentMethod,
                    totalPrice: totalHargaNumeric,
                },
                success: function(data) {
                    console.log('Success Response:', data);

                    if (data.redirect_url) {
                        window.location.href = data
                            .redirect_url; // Arahkan ke URL yang diberikan dari backend
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
    }


    function uploadPayment(orderId) {
        console.log('Order ID:', orderId); // Debugging
        let token = $('meta[name="csrf-token"]').attr('content');
        let totalHarga = $('#totalPrice').text();
        let totalHargaNumeric = parseFloat(totalHarga.replace('Rp. ', '').replace(/,/g, '').replace(/\./g, ''));

        Swal.fire({
            title: `Pembayaran Paket Order`,
            text: `Total Harga: ${totalHarga}`,
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
                formData.append('proof_payment', result.value);
                formData.append('_token', token);
                formData.append('totalPrice', totalHargaNumeric);

                swalProcess();
                $.ajax({
                    url: '{{ url('order/payment') }}/' + orderId,
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
                    url: '{{ url('order/approve') }}/' + id,
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
                    url: '{{ url('order/reject') }}/' + id,
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

    // payOrder input gambar
    // function payOrder(orderId) {
    //     console.log('Order ID:', orderId); // Debugging
    //     let token = $('meta[name="csrf-token"]').attr('content');
    //     let totalHarga = $('#totalPrice').text();
    //     let totalHargaNumeric = parseFloat(totalHarga.replace('Rp. ', '').replace(/,/g, '').replace(/\./g, ''));

    //     Swal.fire({
    //         title: `Pembayaran Paket Order`,
    //         text: `Total Harga: ${totalHarga}`,
    //         html: `
    //         <input type="file" id="uploadImage" class="swal2-file-input" accept="image/*" aria-label="Upload Bukti Pembayaran" style="display: block; margin: 0 auto; width: 100%; padding: 10px ;border: 1px solid #ddd; border-radius: 5px; font-size: 16px; ">`,
    //         showCancelButton: true,
    //         allowOutsideClick: false,
    //         customClass: {
    //             confirmButton: 'btn btn-primary mr-2 mb-3',
    //             cancelButton: 'btn btn-danger mb-3',
    //         },
    //         buttonsStyling: false,
    //         confirmButtonText: 'Upload',
    //         cancelButtonText: 'Cancel',
    //         preConfirm: () => {
    //             const uploadImage = document.getElementById('uploadImage').files[0];
    //             if (!uploadImage) {
    //                 Swal.showValidationMessage('Harap Upload Bukti Pembayaran');
    //                 return false;
    //             }
    //             return uploadImage; // Return the file object
    //         },
    //     }).then(result => {
    //         if (result.isConfirmed) {
    //             const formData = new FormData();
    //             formData.append('proof_payment', result.value);
    //             formData.append('_token', token);
    //             formData.append('totalPrice', totalHargaNumeric);

    //             swalProcess();
    //             $.ajax({
    //                 url: '{{ url('order/payment') }}/' + orderId,
    //                 type: 'POST',
    //                 processData: false, // Important for FormData
    //                 contentType: false, // Important for FormData
    //                 data: formData,
    //                 success: function(data) {
    //                     console.log('Success Response:', data); // Debugging
    //                     location.reload();
    //                 },
    //                 error: function(xhr, error, code) {
    //                     console.log('Error:', xhr, error, code); // Debugging
    //                     swalError(error);
    //                 }
    //             });
    //         }
    //     });
    // }


    //payOrder lama yang ada input select method pembayaran
    // function payOrder(orderId) {
    //     console.log('Payment Order ID:', orderId);
    //     let token = $('meta[name="csrf-token"]').attr('content');

    //     let totalHarga = $('#totalPrice').text();
    //     let totalHargaNumeric = parseFloat(totalHarga.replace('Rp. ', '').replace(/,/g, '').replace(/\./g, ''));



    //     Swal.fire({
    //         title: 'Pembayaran Paket',
    //         text: `Total Harga: ${totalHarga}`,
    //         input: 'select',
    //         inputOptions: {
    //             non_tunai: "Non Tunai",
    //             tunai: "Tunai",
    //         },
    //         inputPlaceholder: "Pilih Metode Pembayaran",
    //         showCancelButton: true,
    //         allowOutsideClick: false,
    //         customClass: {
    //             confirmButton: 'btn btn-primary mr-2 mb-3',
    //             cancelButton: 'btn btn-danger mb-3',
    //         },
    //         buttonsStyling: false,
    //         confirmButtonText: 'Yes',
    //         cancelButtonText: 'Cancel',
    //         preConfirm: (paymentMethod) => {
    //             if (!paymentMethod) {
    //                 Swal.showValidationMessage('Harap pilih metode pembayaran');
    //             }
    //             return paymentMethod;
    //         },
    //     }).then(result => {
    //         if (result.isConfirmed) {
    //             console.log('Metode Pembayaran:', result.value);
    //             swalProcess();
    //             $.ajax({
    //                 url: '{{ url('order/payment') }}/' + orderId,
    //                 type: 'POST',
    //                 cache: false,
    //                 data: {
    //                     _token: token,
    //                     payment_method: result.value,
    //                     totalPrice: totalHargaNumeric,
    //                 },
    //                 success: function(data) {
    //                     console.log('Success Response:', data);
    //                     location.reload();
    //                 },
    //                 error: function(xhr, error, code) {
    //                     console.log('Error:', xhr, error, code);
    //                     swalError(error);
    //                 }
    //             });
    //         }
    //     });
    // }



    // function dataTable() {
    //     const url = $('#url_dt').val();
    //     $('#dt-order').DataTable({
    //         responsive: true,
    //         autoWidth: false,
    //         processing: true,
    //         serverSide: true,
    //         ajax: {
    //             url: url,
    //             error: function(xhr, error, code) {
    //                 swalError(xhr.statusText);
    //             }
    //         },
    //         columns: [{
    //                 data: 'DT_RowIndex',
    //                 width: '5%',
    //                 searchable: false
    //             },
    //             {
    //                 data: 'name',
    //                 defaultContent: '-'
    //             },
    //             {
    //                 data: 'total_price',
    //                 defaultContent: '-'
    //             },
    //             {
    //                 data: 'class',
    //                 defaultContent: '-'
    //             },
    //             {
    //                 data: 'payment_method',
    //                 defaultContent: '-'
    //             },
    //             {
    //                 data: 'status',
    //                 defaultContent: '-'
    //             },
    //             {
    //                 data: 'action',
    //                 width: '20%',
    //                 defaultContent: '-',
    //                 orderable: false,
    //                 searchable: false
    //             },
    //         ]

    //     });

    // }

    // function dataTableAdmin() {
    //     const url = $('#url_dt').val();
    //     $('#dt-order').DataTable({
    //         responsive: true,
    //         autoWidth: false,
    //         processing: true,
    //         serverSide: true,
    //         ajax: {
    //             url: url,
    //             error: function(xhr, error, code) {
    //                 swalError(xhr.statusText);
    //             }
    //         },
    //         columns: [{
    //                 data: 'DT_RowIndex',
    //                 width: '5%',
    //                 searchable: false
    //             },
    //             {
    //                 data: 'name',
    //                 defaultContent: '-'
    //             },
    //             {
    //                 data: 'total_price',
    //                 defaultContent: '-'
    //             },
    //             {
    //                 data: 'class',
    //                 defaultContent: '-'
    //             },
    //             {
    //                 data: 'payment_method',
    //                 defaultContent: '-'
    //             },
    //             {
    //                 data: 'attachment',
    //                 defaultContent: '-'
    //             },
    //             {
    //                 data: 'action',
    //                 width: '20%',
    //                 defaultContent: '-',
    //                 orderable: false,
    //                 searchable: false
    //             },
    //         ]

    //     });

    // }

    // function checkOut(id, name) {
    //     console.log('Checkout ID:', id); // Debugging
    //     let token = $('meta[name="csrf-token"]').attr('content');

    //     Swal.fire({
    //         title: `Checkout Paket: ${name}`,
    //         input: 'select',
    //         inputOptions: {
    //             non_tunai: "Non Tunai",
    //             tunai: "Tunai",
    //         },
    //         inputPlaceholder: "Pilih Metode Pembayaran",
    //         showCancelButton: true,
    //         allowOutsideClick: false,
    //         customClass: {
    //             confirmButton: 'btn btn-primary mr-2 mb-3',
    //             cancelButton: 'btn btn-danger mb-3',
    //         },
    //         buttonsStyling: false,
    //         confirmButtonText: 'Yes',
    //         cancelButtonText: 'Cancel',
    //         preConfirm: (paymentMethod) => {
    //             if (!paymentMethod) {
    //                 Swal.showValidationMessage('Harap pilih metode pembayaran');
    //             }
    //             return paymentMethod;
    //         },
    //     }).then(result => {
    //         if (result.isConfirmed) {
    //             console.log('Metode Pembayaran:', result.value); // Debugging
    //             swalProcess();
    //             $.ajax({
    //                 url: '{{ url('order/checkout') }}/' + id,
    //                 type: 'POST',
    //                 cache: false,
    //                 data: {
    //                     _token: token,
    //                     payment_method: result.value,
    //                 },
    //                 success: function(data) {
    //                     console.log('Success Response:', data); // Debugging
    //                     location.reload();
    //                 },
    //                 error: function(xhr, error, code) {
    //                     console.log('Error:', xhr, error, code); // Debugging
    //                     swalError(error);
    //                 }
    //             });
    //         }
    //     });
    // }
</script>
