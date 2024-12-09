@extends('layouts.section')

@section('content')
    <div class="px-3 py-4">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('class.index') }}" class="btn btn-primary btn-sm my-2"><i
                                class="fas fa-arrow-left"></i> Kembali</a>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="font-weight-bold">{{ $class->package->name }}</h3>
                            </div>
                            <div class="card-body">
                                @if ($listClass->isEmpty())
                                    <form action="{{ route('class.storeMember') }}" method="POST">
                                        @csrf
                                        <div class="table-responsive py-3">
                                            <table id="table-member" class="table table-bordered table-hover text-center">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>No</th>
                                                        <th>Nama Pembeli</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($listOrder as $index => $list)
                                                        <tr>
                                                            <input type="hidden" name="class_id"
                                                                value="{{ $class->id }}">
                                                            <td>
                                                                <input type="checkbox" name="order_package_id[]"
                                                                    value="{{ $list->id }}"
                                                                    id="order_package{{ $index }}">
                                                            </td>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $list->order->user->name ?? 'Nama tidak ditemukan' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <button class="btn btn-success">Simpan</button>
                                    </form>
                                @else
                                    <form method="post" action="{{ route('class.storeClass') }}">
                                        @csrf
                                        <div class="card">
                                            <div class="card-header bg-gradient-gray">
                                                <h5><i class="fas fa-clipboard"></i> KONFIGURASI TEST</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group row">
                                                    <label for="test" class="col-form-label col-sm-2">Test <span
                                                            class="text-danger">*</span></label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" name="test" id="test" required>
                                                            @foreach ($class->package->packageTest as $package)
                                                                <option value="">{{ $package->quiz->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <!-- Label dan Input untuk Tanggal Mulai -->
                                                    <label for="open_quiz" class="col-form-label col-sm-2">Tanggal Mulai
                                                        <span class="text-danger">*</span></label>
                                                    <div class="col-sm-4">
                                                        <input class="form-control @error('open_quiz') is-invalid @enderror"
                                                            type="datetime-local" name="open_quiz" id="open_quiz"
                                                            value="{{ old('open_quiz') }}">
                                                    </div>

                                                    <!-- Label dan Input untuk Tanggal Tutup -->
                                                    <label for="close_quiz" class="col-form-label col-sm-2">Tanggal Tutup
                                                        <span class="text-danger">*</span></label>
                                                    <div class="col-sm-4">
                                                        <input
                                                            class="form-control @error('close_quiz') is-invalid @enderror"
                                                            type="datetime-local" name="close_quiz" id="close_quiz"
                                                            value="{{ old('close_quiz') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header bg-gradient-gray">
                                                <h5><i class="fas fa-user-friends"></i> DAFTAR ANGGOTA</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive py-3">
                                                    <table id="table-member"
                                                        class="table table-bordered table-hover text-center">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>No</th>
                                                                <th>Nama Anggota</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($listClass as $index => $member)
                                                                <tr>
                                                                    <input type="hidden" name="class_id"
                                                                        value="{{ $class->id }}">
                                                                    <td>
                                                                        <input type="checkbox" name="order_package_id[]"
                                                                            value="{{ $member->order_package_id }}"
                                                                            id="order_package{{ $index }}">
                                                                    </td>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $member->orderPackage->order->user->name }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <button class="btn btn-success">Mulai</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @push('javascript-bottom')
        @include('js.myclass.script')
    @endpush
@endsection
