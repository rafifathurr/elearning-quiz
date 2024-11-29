@extends('layouts.section')
@section('content')
    <div class=" py-1">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"></div>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <h3>Selamat Datang, <b>{{ auth()->user()->name }}</b> !</h3>
                    </div>

                    <!-- Daftar Test -->
                    <div class="col-md-6 " style="background-color: #D3E9F6">

                        <h4 class="text-center my-2 ml-2">Daftar Test</h4>
                        <div class="row mx-2">
                            @foreach ($tests as $test)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h6>{{ $test->name }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <p class="fs-6"><i class="fa fa-tags text-danger"></i>
                                                    {{ 'Rp. ' . number_format($test->price, 0, ',', '.') }}</p>
                                            </div>
                                            <ul class="list-unstyled">
                                                @foreach ($test->packageTest as $package)
                                                    <li class="my-1"><i class="fas fa-check text-success"></i>
                                                        {{ $package->quiz->name . ' (' . $package->quiz->type_aspect . ')' }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="card-footer bg-white">
                                            <div class="d-flex justify-content-end">
                                                <button onclick="checkOut({{ $test->id }}, '{{ $test->name }}')"
                                                    class="btn btn-sm btn-primary">Checkout</button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    </div>

                    <!-- Daftar Kelas -->
                    <div class="col-md-6" style="background-color: #D0F8D0">
                        <h4 class="text-center my-2">Daftar Kelas</h4>
                        <div class="row mx-2">
                            @foreach ($classes as $class)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h6>{{ $class->name }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <p class="fs-6"><i class="fa fa-tags text-danger"></i>
                                                    {{ 'Rp. ' . number_format($class->price, 0, ',', '.') }}</p>
                                                <p><i class="fa fa-key text-warning"></i> {{ $class->class }}x Pertemuan
                                                </p>
                                            </div>
                                            <ul class="list-unstyled">
                                                @foreach ($class->packageTest as $package)
                                                    <li class="my-1"><i class="fas fa-check text-success"></i>
                                                        {{ $package->quiz->name . ' (' . $package->quiz->type_aspect . ')' }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="card-footer bg-white">
                                            <div class="d-flex justify-content-end">
                                                <button onclick="checkOut({{ $class->id }}, '{{ $class->name }}')"
                                                    class="btn btn-sm btn-primary">Gabung</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @push('javascript-bottom')
        <script>
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
        </script>
    @endpush
@endsection
