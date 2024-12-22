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

    function addTest(id) {
        let token = $('meta[name="csrf-token"]').attr('content');
        console.log('Class ID:', id);

        // Map packageTests ke format opsi Swal
        const options = packageTests.reduce((acc, test) => {
            acc[test.id] = test.name;
            return acc;
        }, {});

        // Menampilkan Swal dengan input select dan input datetime-local untuk waktu buka/tutup
        Swal.fire({
            title: 'Tambah Test',
            html: `
    <style>
        @media (max-width: 768px) {
            .responsive-label,
            .responsive-input {
                width: 100% !important;
                margin-right: 0 !important; /* Menghapus margin agar label/input tidak tumpang tindih */
            }
            .responsive-label {
                text-align: left !important; /* Pastikan label rata kiri */
            }
            .responsive-container {
                flex-direction: column; /* Atur container agar elemen vertikal */
                align-items: flex-start; /* Pastikan elemen berada di kiri */
            }
        }
    </style>
    <div class="responsive-container" style="display: flex; align-items: center; margin-bottom: 10px;">
        <label for="test" class="responsive-label" style="width: 30%; margin-right: 10px;">Pilih Test</label>
        <select id="test" class="swal2-select responsive-input" style="width: 70%; padding: 8px;">
            ${Object.entries(options).map(([id, name]) => `
                <option value="${id}">${name}</option>
            `).join('')}
        </select>
    </div>

    <div class="responsive-container" style="display: flex; align-items: center; margin-bottom: 10px;">
        <label for="open_quiz" class="responsive-label" style="width: 30%; margin-right: 10px;">Tanggal Mulai</label>
        <input id="open_quiz" type="datetime-local" class="swal2-input responsive-input" style="width: 70%; padding: 8px;" required>
    </div>

    <div class="responsive-container" style="display: flex; align-items: center;">
        <label for="close_quiz" class="responsive-label" style="width: 30%; margin-right: 10px;">Tanggal Tutup</label>
        <input id="close_quiz" type="datetime-local" class="swal2-input responsive-input" style="width: 70%; padding: 8px;" required>
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
                console.log('PreConfirm triggered');
                const testId = document.getElementById('test').value;
                const openQuiz = document.getElementById('open_quiz').value;
                const closeQuiz = document.getElementById('close_quiz').value;

                console.log('Test ID:', testId, 'Open Quiz:', openQuiz, 'Close Quiz:', closeQuiz);

                // Validasi input
                if (!testId || !openQuiz || !closeQuiz) {
                    Swal.showValidationMessage('Semua bidang harus diisi');
                }

                return {
                    quiz_id: testId,
                    open_quiz: openQuiz,
                    close_quiz: closeQuiz,
                    class_id: id,
                };
            },
            willOpen: () => {
                // Memberikan CSS tambahan agar lebih responsif dan mencegah scroll horizontal
                const swalContent = document.querySelector('.swal2-html-container');

                // Mengatur lebar maksimal dan menyembunyikan scroll horizontal
                swalContent.style.overflowX = 'hidden';
                swalContent.style.maxWidth = '100%'; // Mencegah lebar lebih dari 100%
            }
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
</script>
