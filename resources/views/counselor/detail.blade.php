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
                                <!--Daftar Konselor -->
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="m-0 font-weight-bold">
                                            <i class="fas fa-user-tie mr-2"></i> KONSELOR KELAS
                                        </h5>
                                    </div>
                                    <div class="card-body p-3">
                                        @if ($class->classCounselor->isNotEmpty())
                                            <ul class="list-group list-group-flush">
                                                @foreach ($class->classCounselor as $item)
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <i class="fas fa-user-circle text-info mr-2"></i>
                                                        <span class="font-weight-bold">{{ $item->counselor->name }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted text-center m-0">Belum ada konselor yang ditugaskan.
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                @hasanyrole('counselor')
                                    @if ($isCounselor)
                                        <button onclick="addTest({{ $class->id }})" class="btn btn-danger mb-3"
                                            {{ $class->current_meeting == $class->total_meeting ? 'disabled' : '' }}> <i
                                                class="fas fa-book-medical mr-1"></i> Aktivasi
                                            Test</button>
                                    @endif
                                @endhasanyrole

                                <!--Daftar Test -->
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="m-0 font-weight-bold">
                                            <i class="fas fa-book-open mr-2"></i> TEST YANG SUDAH DIBERIKAN
                                        </h5>
                                    </div>
                                    <div class="card-body p-3">
                                        @if ($givenTests->isEmpty())
                                            <p class="text-muted">Belum ada test yang diberikan.</p>
                                        @else
                                            <ul class="list-group" style="overflow: auto; max-height: 300px;">
                                                @foreach ($givenTests as $test)
                                                    <li class="list-group-item">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <strong>{{ $test->quiz->name }}</strong> <br>
                                                                <small>🕒 Dibuka:
                                                                    {{ $test->open_quiz ? \Carbon\Carbon::parse($test->open_quiz)->translatedFormat('d F Y H:i') : 'jadwal tidak dipilih' }}
                                                                    - Ditutup:
                                                                    {{ $test->close_quiz ? \Carbon\Carbon::parse($test->close_quiz)->translatedFormat('d F Y H:i') : 'jadwal tidak dipilih' }}</small>
                                                            </div>
                                                            <button class="btn btn-outline-info  btn-sm"
                                                                onclick="updateTest({{ $test->id }})"><i
                                                                    class="fas fa-edit"></i></button>
                                                        </div>

                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>


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
                                                <span class="font-weight-bold badge bg-lightblue p-2 flex-wrap text-wrap"
                                                    style="font-size: 1rem; white-space: normal; word-wrap: break-word;">
                                                    Jadwal Kelas: {{ $class->class_date }}
                                                </span>
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
                                            @hasanyrole('counselor')
                                                <button type="submit" class="btn btn-success m-2">Absensi</button>
                                            @endhasanyrole
                                        </form>
                                    </div>
                                @else
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
                                            <span class="font-weight-bold badge bg-lightblue p-2 flex-wrap text-wrap"
                                                style="font-size: 1rem; white-space: normal; word-wrap: break-word;">
                                                Jadwal Kelas: {{ $class->class_date }}
                                            </span>
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

                                    @hasanyrole('counselor')
                                        @if ($isCounselor)
                                            {{-- Pastikan hanya counselor kelas ini yang bisa melihat tombol --}}
                                            @if (!$selectedDate)
                                                <button class="btn btn-success my-3"
                                                    {{ $latestAttendance || $class->current_meeting == $class->total_meeting ? 'disabled' : '' }}>Absensi</button>
                                                @if ($latestAttendance)
                                                    <button class="btn btn-warning my-3">Ubah Absensi</button>
                                                @endif
                                            @endif
                                        @endif

                                        </form>
                                    @endhasanyrole
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

            // Ambil semua test dari database (untuk kondisi jika paket tidak memiliki test)
            const allTests = @json(
                \App\Models\Quiz\Quiz::all()->map(function ($quiz) {
                    return ['id' => $quiz->id, 'name' => $quiz->name];
                }));

            // Ambil test dari paket jika ada
            const packageTests = @json(
                $class->package->packageTest->map(function ($package) {
                    return ['id' => $package->quiz->id, 'name' => $package->quiz->name];
                }));

            // Jika paket memiliki test, gunakan packageTests, jika tidak gunakan allTests
            const selectedTests = packageTests.length > 0 ? packageTests : allTests;
        </script>
    @endpush
@endsection
