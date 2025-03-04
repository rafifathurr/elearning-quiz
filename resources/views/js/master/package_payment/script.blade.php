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
        $('#dt-package').DataTable({
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
                    data: 'type_package',
                    defaultContent: '-'
                },
                {
                    data: 'class',
                    defaultContent: '-'
                },
                {
                    data: 'max_member',
                    defaultContent: '-'
                },
                {
                    data: 'date_class',
                    defaultContent: '-'
                },
                {
                    data: 'price',
                    defaultContent: '-'
                },
                {
                    data: 'quiz',
                    width: '25%',
                    defaultContent: '-'
                },
                {
                    data: 'status',
                    defaultContent: '-'
                },
                {
                    data: 'action',
                    defaultContent: '-',
                    width: '10%',
                    orderable: false,
                    searchable: false
                },
            ],
            rowCallback: function(row, data) {
                let token = $('meta[name="csrf-token"]').attr('content');
                // Menambahkan event listener untuk toggle status
                $('input[type="checkbox"]', row).on('change', function() {
                    var status = $(this).prop('checked') ? 1 :
                        0; // Set status 1 jika checked, 0 jika unchecked
                    var id = data.id; // Ambil ID data

                    // Kirim AJAX untuk update status
                    $.ajax({
                        url: '{{ url('master/package/update-status') }}/' + id,
                        method: 'POST',
                        data: {
                            _token: token,
                            status: status
                        },
                        success: function(response) {
                            // Update toggle checkbox untuk refleksi status yang baru
                            if (status === 1) {
                                // Jika status aktif, set checkbox tercentang
                                $('input[type="checkbox"]', row).prop('checked', true);
                            } else {
                                // Jika status tidak aktif, hilangkan tanda centang
                                $('input[type="checkbox"]', row).prop('checked', false);
                            }

                            // Update label status (opsional, untuk memberikan informasi lebih lanjut)
                            var label = $('label', row);
                            if (status === 1) {
                                label.text('Aktif');
                            } else {
                                label.text('Tidak Aktif');
                            }
                        },
                        error: function(xhr) {
                            // Jika ada error, beri tahu pengguna
                            swalError('Terjadi kesalahan, coba lagi.');
                        }
                    });
                });
            }


        });

    }

    function exportPackage() {
        const currentYear = new Date().getFullYear();
        let yearOptions = '<option value="all">Semua Tahun</option>';

        for (let i = currentYear; i >= currentYear - 5; i--) {
            yearOptions += `<option value="${i}">${i}</option>`;
        }

        Swal.fire({
            title: 'Pilih Bulan & Tahun',
            html: `
        <select id="selected_month" class="form-control">
           <option value="all">Semua Bulan</option>
            <option value="01">Januari</option>
            <option value="02">Februari</option>
            <option value="03">Maret</option>
            <option value="04">April</option>
            <option value="05">Mei</option>
            <option value="06">Juni</option>
            <option value="07">Juli</option>
            <option value="08">Agustus</option>
            <option value="09">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12">Desember</option>
        </select>
        <br>
        <select id="selected_year" class="form-control">
            ${yearOptions}
        </select>
        `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Export',
            cancelButtonText: 'Batal',
            didOpen: () => {
                document.getElementById('selected_month').focus();
            },
            preConfirm: () => {
                const selectedMonth = document.getElementById('selected_month').value;
                const selectedYear = document.getElementById('selected_year').value;

                if (!selectedMonth || !selectedYear) {
                    Swal.showValidationMessage('Silakan pilih bulan dan tahun!');
                    return false;
                }
                return {
                    month: selectedMonth,
                    year: selectedYear
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Arahkan ke route export dengan parameter bulan & tahun
                window.location.href =
                    `{{ url('master/package/exportData') }}?month=${result.value.month}&year=${result.value.year}`

            }
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
                    url: '{{ url('master/package') }}/' + id,
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
</script>
