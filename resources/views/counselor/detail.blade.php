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
                                <button onclick="addTest({{ $class->id }})" class="btn btn-primary mb-3">Tambah
                                    Test</button>
                                @if ($listClass->isEmpty())
                                    <form action="{{ route('class.storeMember') }}" method="POST">
                                        @csrf

                                        <div class="card">
                                            <div class="card-header bg-gray-light">
                                                <h5><i class="fas fa-user-friends"></i> DAFTAR PENGGUNA</h5>
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
                                            </div>
                                        </div>
                                        <button class="btn btn-success">Mulai Kelas</button>
                                    </form>
                                @else
                                    <form method="post" action="{{ route('class.storeMember') }}">
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
        <script>
            const packageTests = @json(
                $class->package->packageTest->map(function ($package) {
                    return ['id' => $package->quiz->id, 'name' => $package->quiz->name];
                }));
        </script>
    @endpush
@endsection
