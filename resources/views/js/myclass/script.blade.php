<script type="text/javascript">
    $(document).ready(function() {
        $('#table-member').DataTable();
    });
    $('form').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Apakah Anda Ingin Memilih Anggota Ini?',
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
                $('form').unbind('submit').submit();
            }
        })
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
            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                <label for="test" style="width: 30%; margin-right: 10px;">Pilih Test</label>
                <select id="test" class="swal2-select" style="width: 70%; padding: 8px;">
                    ${Object.entries(options).map(([id, name]) => `
                        <option value="${id}">${name}</option>
                    `).join('')}
                </select>
            </div>

            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                <label for="open_quiz" style="width: 30%; margin-right: 10px;">Tanggal Mulai</label>
                <input id="open_quiz" type="datetime-local" class="swal2-input" style="width: 70%; padding: 8px;" required>
            </div>

            <div style="display: flex; align-items: center;">
                <label for="close_quiz" style="width: 30%; margin-right: 10px;">Tanggal Tutup</label>
                <input id="close_quiz" type="datetime-local" class="swal2-input" style="width: 70%; padding: 8px;" required>
            </div>
        `,
            showCancelButton: true,
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-primary mr-2 mb-3',
                cancelButton: 'btn btn-danger mb-3',
            },
            buttonsStyling: false,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
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
</script>
