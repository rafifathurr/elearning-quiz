@extends('layouts.section')
@section('content')
    <style>
        input[type="checkbox"].custom-disabled:disabled:checked {
            appearance: none;
            /* Hilangkan tampilan default browser */
            -webkit-appearance: none;

            width: 20px;
            /* Ukuran kotak checkbox */
            height: 20px;
            /* Sesuaikan dengan kebutuhan */
            display: inline-block;
            position: relative;
            cursor: not-allowed;
            /* Menandakan checkbox tidak bisa diubah */
        }

        input[type="checkbox"].custom-disabled:disabled:checked::after {
            content: '✔';
            /* Tanda centang */
            color: white;
            /* Warna centang */
            font-size: 14px;
            display: block;
            text-align: center;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
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
                                    <h4 class="font-weight-bold">{{ $class->name }}</h4>
                                    @if ($listClass->isEmpty())
                                        <h4 class="font-weight-bold">
                                            {{ 'Pertemuan ' . $class->current_meeting + 1 }}
                                        </h4>
                                    @else
                                        <form method="get" id="filter-form"
                                            action="{{ route('class.show', $class->id) }}">
                                            <select name="filter_data" id="filter_data" class="form-control"
                                                onchange="document.getElementById('filter-form').submit();">
                                                <option value="">Pilih Tanggal</option>
                                                @foreach ($filterDate as $filter)
                                                    <option value="{{ $filter->attendance_date }}"
                                                        {{ $selectedDate == $filter->attendance_date ? 'selected' : '' }}>
                                                        {{ \Carbon\Carbon::parse($filter->attendance_date)->translatedFormat('d F Y') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    @endif
                                </div>

                            </div>
                            <div class="card-body">
                                @if ($listClass->isEmpty())
                                    <!-- Form Daftar Peserta -->
                                    <div class="card">
                                        <form id="form-daftar-peserta" action="{{ route('class.storeAttendance') }}"
                                            method="POST">
                                            @csrf
                                            <div class="card-header bg-lightblue d-flex justify-content-between">
                                                <h5 class="w-100 text-left font-weight-bold"><i
                                                        class="fas fa-user-friends"></i>
                                                    DAFTAR
                                                    PESERTA</h5>
                                                @if (isset($class->package->max_member) && $class->package->max_member > 0)
                                                    <h5 class="w-100 text-right font-weight-bold ">Max:
                                                        {{ $class->package->max_member . ' Peserta' }}</h5>
                                                @endif
                                            </div>
                                            <div class="card-body">
                                                <span class="font-weight-bold badge bg-lightblue p-2"
                                                    style="font-size: 1rem">Jadwal Kelas:
                                                    {{ $class->class_date }}</span>
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
                                                            @if (!empty($listMember))
                                                                @foreach ($listMember as $index => $list)
                                                                    <tr>
                                                                        <input type="hidden" name="class_id"
                                                                            value="{{ $class->id }}">
                                                                        <td>
                                                                            <input type="checkbox"
                                                                                name="attendance[{{ $list->order_package_id }}]"
                                                                                value="1">
                                                                        </td>
                                                                        <td>{{ $loop->iteration }}</td>
                                                                        <td>{{ $list->user->name }}</td>
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

                                    @if ($latestAttendance)
                                        <form method="post" id="form-daftar-peserta"
                                            action="{{ route('class.updateAttendance') }}">
                                        @else
                                            <form method="post" id="form-daftar-peserta"
                                                action="{{ route('class.storeAttendance') }}">
                                    @endif

                                    @csrf
                                    <div class="card">
                                        <div class="card-header bg-lightblue d-flex justify-content-between">
                                            <h5 class="w-100 text-left font-weight-bold"><i class="fas fa-user-friends"></i>
                                                DAFTAR PESERTA</h5>
                                            @if (isset($class->package->max_member) && $class->package->max_member > 0)
                                                <h5 class="w-100 text-right font-weight-bold ">Max:
                                                    {{ $class->package->max_member . ' Peserta' }}</h5>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <span class="font-weight-bold badge bg-lightblue p-2"
                                                style="font-size: 1rem">Jadwal Kelas:
                                                {{ $class->class_date }}</span>
                                            <div class="table-responsive py-3">
                                                <table id="table-member"
                                                    class="table table-bordered table-hover text-center">
                                                    <thead>
                                                        <tr>
                                                            <th>Kehadiran</th>
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
                                                                        value="1"
                                                                        class="{{ $selectedDate ? 'custom-disabled' : '' }}"
                                                                        {{ $member->attendance == 1 ? 'checked' : '' }}
                                                                        {{ $selectedDate ? 'disabled' : '' }}>
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

                                    @if (!$selectedDate)
                                        <button class="btn btn-success my-3"
                                            {{ $latestAttendance || $class->current_meeting == $class->total_meeting ? 'disabled' : '' }}>Absensi</button>
                                        @if ($latestAttendance)
                                            <button class="btn btn-warning my-3">Ubah Absensi</button>
                                        @endif
                                    @endif
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
