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
                            <div class="card-header ">
                                <div class="d-flex justify-content-between">
                                    <h4 class="font-weight-bold">{{ $class->package->name }}</h4>
                                    <h4 class="font-weight-bold">
                                        {{ 'Pertemuan ' . $class->current_meeting + 1 }}
                                    </h4>
                                </div>

                            </div>
                            <div class="card-body">
                                @if ($listClass->isEmpty())
                                    <!-- Form Tambah Peserta -->
                                    <div class="card">
                                        <div class="card-header bg-gray-light">
                                            <h5><i class="fas fa-user-edit"></i> TAMBAH PESERTA</h5>
                                        </div>
                                        <div class="card-body">
                                            <form id="form-tambah-peserta" action="{{ route('class.storeMember') }}"
                                                method="POST">
                                                @csrf
                                                <div class="form-group row">
                                                    <label for="order_package_id"
                                                        class="col-md-4 control-label text-left">Tambah
                                                        Peserta
                                                        <span class="text-danger ml-1">*</span>
                                                    </label>
                                                    <div class="col-md-8 col-sm-12">
                                                        <input type="hidden" name="class_id" value="{{ $class->id }}">
                                                        <select
                                                            class="form-control @error('order_package_id[]') is-invalid @enderror"
                                                            name="order_package_id[]" id="order_package_id"
                                                            data-placeholder="Pilih Peserta" style="width: 100%;" required>
                                                            @foreach ($listOrder as $order)
                                                                <option value="{{ $order->id }}">
                                                                    {{ $order->order->user->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('order_package_id[]')
                                                            <div class="alert alert-danger mt-2">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <!-- Gunakan atribut form untuk memastikan submit form -->
                                                    <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- Form Daftar Peserta -->
                                    <div class="card">
                                        <form id="form-daftar-peserta" action="{{ route('class.storeAttendance') }}"
                                            method="POST">
                                            @csrf
                                            <div class="card-header bg-gray-light">
                                                <h5><i class="fas fa-user-friends"></i> DAFTAR PESERTA</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive py-3">
                                                    <table id="table-member"
                                                        class="table table-bordered table-hover text-center">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>No</th>
                                                                <th>Nama</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (!empty($listMember))
                                                                @foreach ($listMember as $index => $list)
                                                                    <tr>
                                                                        <input type="hidden" name="class_id"
                                                                            value="{{ $class->id }}">
                                                                        <td>
                                                                            <input type="checkbox" name="order_package_id[]"
                                                                                value="{{ $list['order_package_id'] }}"
                                                                                id="order_package{{ $index }}">
                                                                        </td>
                                                                        <td>{{ $loop->iteration }}</td>
                                                                        <td>{{ $list['user_name'] }}</td>
                                                                        </td>
                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-danger"
                                                                                onclick="removeMember({{ $index }})">Hapus</button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td colspan="4">Belum ada data peserta.</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-success m-2">Absensi</button>
                                        </form>
                                    </div>
                                @else
                                    <button onclick="addTest({{ $class->id }})" class="btn btn-primary mb-3"
                                        {{ $class->current_meeting == $class->total_meeting ? 'disabled' : '' }}>Aktivasi
                                        Test</button>
                                    <form method="post" id="form-daftar-peserta"
                                        action="{{ route('class.storeAttendance') }}">
                                        @csrf
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
                                                                        <input type="checkbox"
                                                                            name="attendance[{{ $member->orderPackage->id }}]"
                                                                            value="1">
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

                                        <button class="btn btn-success my-3"
                                            {{ $class->current_meeting == $class->total_meeting ? 'disabled' : '' }}>Absensi</button>
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
        <script>
            $('#order_package_id').select2({
                multiple: true,
            });

            $('#order_package_id').val('').trigger('change');

            const packageTests = @json(
                $class->package->packageTest->map(function ($package) {
                    return ['id' => $package->quiz->id, 'name' => $package->quiz->name];
                }));
        </script>
    @endpush
@endsection
