@extends('layouts.section')

@section('content')
    <div class="px-3 py-4">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="font-weight-bold">{{ $packageOrder->first()->package->name }}</h3>
                            </div>
                            <div class="card-body">
                                <h5 class="font-weight-bolder">Daftar Anggota:</h5>
                                <form method="post" action="{{ route('class.store') }}">
                                    @csrf
                                    <div class="table-responsive py-3">
                                        <table id="table-member" class="table table-bordered table-hover text-center">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>No</th>
                                                    <th>Nama Anggota</th>
                                                    <th>Pertemuan Ke-</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($packageOrder as $index => $package)
                                                    <tr>
                                                        <input type="hidden" name="package_id"
                                                            value="{{ $package->package_id }}">
                                                        <td>
                                                            <input type="checkbox" name="user[]"
                                                                value="{{ $package->order->user->id }}"
                                                                id="user_{{ $index }}">
                                                        </td>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $package->order->user->name }}</td>
                                                        <td>
                                                            {{ $package->current_class == 0 ? 'Belum Ada Pertemuan' : 'Pertemuan ' . $package->current_class }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <button class="btn btn-success">Mulai</button>
                                </form>
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
