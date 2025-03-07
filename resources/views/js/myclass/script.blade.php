<script type="text/javascript">
    $(document).ready(function() {
        $('#table-member').DataTable();
    });

    $('#form-tambah-peserta').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda Ingin Menambahkan Peserta Ini?',
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
                $('#form-tambah-peserta').unbind('submit').submit();
            }
        });
    });


    $('#form-daftar-peserta').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda Yakin Ingin Melakukan Absensi?',
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
                $('#form-daftar-peserta').unbind('submit').submit()
            }
        });
    });

    function dataTable() {
        const url = $('#url_dt').val();
        $('#dt-myclass').DataTable({
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
                    data: 'class_name',
                    defaultContent: '-'
                },
                {
                    data: 'class_counselor',
                    defaultContent: '-'
                },
                {
                    data: 'class',
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

    function dataTableAttendance() {

        $('#dt-attendance').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: $('#url').val(),
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
                    data: 'attendance_date',
                    defaultContent: '-'
                },
                {
                    data: 'status',
                    defaultContent: '-'
                },
            ]

        });

    }

    function dataTableDetail() {
        const url = $('#url_dt').val();
        $('#dt-myclass').DataTable({
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
                    data: 'quiz',
                    defaultContent: '-'
                },
                {
                    data: 'type_quiz',
                    defaultContent: '-'
                },
                {
                    data: 'open_quiz',
                    defaultContent: '-'
                },
                {
                    data: 'close_quiz',
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
        $('#dt-classadmin').DataTable({
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
                    data: 'current_meeting',
                    defaultContent: '-'
                },
                {
                    data: 'total_meeting',
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

    $(document).on('click', '.btn-lanjutkan', function(e) {
        e.preventDefault(); // Mencegah navigasi default sementara
        localStorage.removeItem('remainingTime'); // Hapus data dari local storage

        // Arahkan ke URL dari tombol "Lanjutkan"
        const url = $(this).attr('href');
        window.location.href = url;
    });

    function addTest(id) {
        let token = $('meta[name="csrf-token"]').attr('content');
        console.log('Class ID:', id);

        // Map selectedTests ke format opsi Swal
        const options = selectedTests.reduce((acc, test) => {
            acc[test.id] = test.name;
            return acc;
        }, {});

        Swal.fire({
            title: 'Tambah Test',
            html: `
            <style>
                .responsive-container {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    margin-bottom: 10px;
                    width: 100%;
                }
                .responsive-label {
                    min-width: 150px; /* Ukuran seragam untuk label */
                    text-align: left;
                    font-weight: bold;
                }
                .responsive-input {
                    flex: 1; /* Menyesuaikan ukuran input agar sejajar */
                    padding: 8px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                }
                @media (max-width: 768px) {
                    .responsive-container {
                        flex-direction: column;
                        align-items: flex-start;
                    }
                    .responsive-label,
                    .responsive-input {
                        width: 100% !important;
                    }
                }
            </style>
            <div class="responsive-container">
                <label for="test" class="responsive-label">Pilih Test</label>
                <select id="test" class="responsive-input">
                    <option value="">-- Pilih Test --</option>
                    ${Object.entries(options).map(([id, name]) => `
                        <option value="${id}">${name}</option>
                    `).join('')}
                </select>
            </div>

            <div class="responsive-container">
                <label for="open_quiz" class="responsive-label">Tanggal Mulai</label>
                <input id="open_quiz" type="datetime-local" class="responsive-input" required>
            </div>

            <div class="responsive-container">
                <label for="close_quiz" class="responsive-label">Tanggal Tutup</label>
                <input id="close_quiz" type="datetime-local" class="responsive-input" required>
            </div>
        `,

            showCancelButton: true,
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-primary mr-2 mb-3',
                cancelButton: 'btn btn-danger mb-3',
            },
            buttonsStyling: false,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const testId = document.getElementById('test').value;
                const openQuiz = document.getElementById('open_quiz').value;
                const closeQuiz = document.getElementById('close_quiz').value;

                if (!testId || !openQuiz || !closeQuiz) {
                    Swal.showValidationMessage('Semua bidang harus diisi');
                    return false;
                }

                return {
                    quiz_id: testId,
                    open_quiz: openQuiz,
                    close_quiz: closeQuiz,
                    class_id: id,
                };
            },
            willOpen: () => {
                $('.swal2-html-container').css({
                    'overflow-x': 'hidden',
                    'max-width': '100%'
                });
            },
            didOpen: () => {
                $('#test').select2({
                    placeholder: 'Cari Test...',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('.swal2-popup')
                });
            },
        }).then(result => {
            if (result.isConfirmed) {
                swalProcess(); // Menampilkan proses loading

                $.ajax({
                    url: '{{ url('class/store-test') }}',
                    type: 'POST',
                    cache: false,
                    data: {
                        _token: token,
                        class_id: id,
                        quiz_id: result.value.quiz_id,
                        open_quiz: result.value.open_quiz,
                        close_quiz: result.value.close_quiz
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
    }



    function removeMember(index) {
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
                    url: '{{ url('class/remove-member') }}/' + index,
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

    function exportClass() {
        // Ambil daftar paket dari backend
        $.ajax({
            url: "{{ url('class/getPackages') }}", // Endpoint untuk mendapatkan daftar paket
            method: "GET",
            success: function(packageResponse) {
                console.log(packageResponse);
                let packageOptions = '<option value="" disabled selected>Pilih Paket</option>';

                packageResponse.packages.forEach(pkg => {
                    let typeName = pkg.type_package ? pkg.type_package.name : 'Tidak Ada Tipe';
                    packageOptions +=
                        `<option value="${pkg.id}">${pkg.name} | ${typeName}</option>`;
                });


                Swal.fire({
                    title: 'Pilih Paket, Bulan & Tahun',
                    html: `
                <select id="selected_package" class="form-control">
                    ${packageOptions}
                </select>
                `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Export',
                    cancelButtonText: 'Batal',
                    didOpen: () => {
                        $('#selected_package').select2({
                            placeholder: 'Cari Paket...',
                            allowClear: true,
                            dropdownParent: $('.swal2-popup')
                        });
                        document.getElementById('selected_package').focus();
                    },
                    preConfirm: () => {
                        const selectedPackage = document.getElementById('selected_package')
                            .value;

                        if (!selectedPackage) {
                            Swal.showValidationMessage(
                                'Silakan pilih paket');
                            return false;
                        }
                        return {
                            package: selectedPackage,
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Arahkan ke route export dengan parameter bulan, tahun, dan paket
                        window.location.href =
                            `{{ url('class/exportData') }}?package=${result.value.package}`;
                    }
                });
            },
            error: function() {
                Swal.fire("Error", "Gagal mengambil daftar paket", "error");
            }
        });
    }
</script>
