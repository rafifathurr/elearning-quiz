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
                $('form').unbind('submit').submit();
            }
        })
    });

    function dataTable() {
        const url = $('#url_dt').val();
        $('#dt-user').DataTable({
            autoWidth: false,
            responsive: true,
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
                    defaultContent: '-',
                },
                {
                    data: 'username',
                    defaultContent: '-',
                },
                {
                    data: 'email',
                    defaultContent: '-',
                },
                {
                    data: 'role',
                    defaultContent: '-',
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
        }).then((result) => {
            if (result.isConfirmed) {
                swalProcess();
                $.ajax({
                    url: '{{ url('master/user') }}/' + id,
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

    document.addEventListener('DOMContentLoaded', function() {
        const password = document.getElementById('password');
        const re_password = document.getElementById('re_password');
        const phone = document.getElementById('phone');

        function checkRePassword(event) {
            if (password.value.length > 0) {
                re_password.setAttribute('required', 'required'); // Set required jika password diisi
            } else {
                re_password.removeAttribute('required'); // Hapus required jika password kosong
            }

            if (re_password.value !== password.value) {
                re_password.setCustomValidity('Password Tidak Sama.');
            } else {
                re_password.setCustomValidity('');
            }
        }

        function validateInput(event) {
            var input = event.target;
            input.value = input.value.replace(/\D/g, ''); // Hapus karakter non-angka

            if (input.id === 'phone' && (input.value.length < 10 || input.value.length > 13)) {
                input.setCustomValidity('Nomor HP terdiri dari 10 sampai 13 angka.');
            } else {
                input.setCustomValidity('');
            }
        }

        password.addEventListener('input', checkRePassword);
        re_password.addEventListener('input', checkRePassword);
        phone.addEventListener('input', validateInput);
    });
</script>
