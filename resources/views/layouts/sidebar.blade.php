<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <?php
    $result = App\Models\Result::where('user_id', Auth::id())->whereNull('finish_time')->first();
    if (App\Models\User::find(Auth::user()->id)->hasRole('user') && $result) {
        $display = 'd-none';
    } else {
        $display = 'd-block';
    } ?>

    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('img/bclogo.png') }}" alt="AdminLTE Logo" class="brand-image " style="opacity: .8">
        <span class="brand-text font-weight-bold text-uppercase text-truncate">Brata Cerdas</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('dist/adminlte/img/user.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="my-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item {{ $display }}">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>


                <?php
                $user = Auth::user(); // Simpan instance user
                
                // Cek peran pengguna
                $isUser = $user->hasRole('user');
                $isCounselor = $user->hasRole('counselor');
                
                if ($isUser && !$isCounselor) {
                    // Jika hanya 'user' tanpa 'counselor'
                    $orderIds = App\Models\Order::whereNull('deleted_at')->where('user_id', $user->id)->whereNull('order_by')->where('status', 1)->pluck('id');
                } else {
                    // Jika 'counselor' atau memiliki kedua role 'user' dan 'counselor'
                    $orderIds = App\Models\Order::whereNull('deleted_at')->where('order_by', $user->id)->where('status', 1)->pluck('id');
                }
                
                $orderPackage = App\Models\OrderPackage::whereIn('order_id', $orderIds)->whereNull('deleted_at')->count();
                
                $orderList = App\Models\Order::whereNull('deleted_at')->where('status', 10)->count();
                $historyOrder = App\Models\Order::whereNull('deleted_at')
                    ->where(function ($query) {
                        $query->where('user_id', Auth::user()->id)->orWhere('order_by', Auth::user()->id);
                    })
                    ->where('status', 2)
                    ->count();
                
                // Cek apakah salah satu menu order sedang aktif
                $orderActive = request()->routeIs('order.index') || request()->routeIs('order.history') || request()->routeIs('order.listOrder') || request()->routeIs('mytest.index') || request()->routeIs('myclass.index') || request()->routeIs('master.dateclass.index') || request()->routeIs('class.index') || request()->routeIs('master.member.index') ? 'menu-open' : '';
                
                ?>

                <li class="nav-item {{ $display }} {{ $orderActive }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Order
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @hasanyrole('user|counselor')
                            <li class="nav-item {{ $display }}">

                                <a href="{{ route('order.index') }}"
                                    class="nav-link {{ request()->routeIs('order.index') ? 'bg-primary' : '' }}">
                                    <i class="nav-icon fas fa-shopping-cart"></i>
                                    <p>
                                        My Order <span
                                            class="badge badge-danger ml-1 position-absolute">{{ $orderPackage > 0 ? $orderPackage : '' }}</span>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item {{ $display }}">
                                <a href="{{ route('order.history') }}"
                                    class="nav-link {{ request()->routeIs('order.history') ? 'bg-primary' : '' }}">
                                    <i class="nav-icon fas fa-list-alt"></i>
                                    <p>
                                        Order List <span
                                            class="badge badge-danger ml-1 position-absolute">{{ $historyOrder > 0 ? $historyOrder : '' }}</span>
                                    </p>
                                </a>
                            </li>
                        @endhasanyrole


                        @hasanyrole('admin|finance|manager')
                            <li class="nav-item {{ $display }}">
                                <a href="{{ route('order.listOrder') }}"
                                    class="nav-link {{ request()->routeIs('order.listOrder') ? 'bg-primary' : '' }}">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>
                                        Daftar Order
                                        @hasanyrole('admin|finance')
                                            @if ($orderList > 0)
                                                <span
                                                    class="badge badge-danger ml-1 position-absolute">{{ $orderList > 0 ? $orderList : '' }}
                                                </span>
                                            @endif
                                        @endhasanyrole

                                    </p>
                                </a>
                            </li>
                        @endhasanyrole
                        @hasrole('user')
                            <li class="nav-item {{ $display }}">
                                <a href="{{ route('mytest.index') }}"
                                    class="nav-link {{ request()->routeIs('mytest.index') ? 'bg-primary' : '' }}">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>
                                        My Test
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item {{ $display }}">
                                <a href="{{ route('myclass.index') }}"
                                    class="nav-link {{ request()->routeIs('myclass.index') ? 'bg-primary' : '' }}">
                                    <i class="nav-icon fas fa-chalkboard"></i>
                                    <p>
                                        My Class
                                    </p>
                                </a>
                            </li>
                        @endhasrole

                        @hasanyrole('admin|manager')
                            <li class="nav-item {{ $display }}">
                                <a href="{{ route('master.dateclass.index') }}"
                                    class="nav-link {{ request()->routeIs('master.dateclass.index') ? 'bg-primary' : '' }}">
                                    <i class="nav-icon fas fa-calendar-alt"></i>
                                    <p>
                                        Jadwal Kelas
                                    </p>
                                </a>
                            </li>
                        @endhasanyrole

                        @hasanyrole('counselor|class-operator|manager')
                            <li class="nav-item {{ $display }}">
                                <a href="{{ route('class.index') }}"
                                    class="nav-link {{ request()->routeIs('class.index') ? 'bg-primary' : '' }}">
                                    <i class="nav-icon fas fa-chalkboard"></i>
                                    <p>
                                        My Class Konselor
                                    </p>
                                </a>
                            </li>
                            @hasanyrole('class-operator|manager')
                                <li class="nav-item {{ $display }}">
                                    <a href="{{ route('master.member.index') }}"
                                        class="nav-link {{ request()->routeIs('master.member.index') ? 'bg-primary' : '' }}">
                                        <i class="nav-icon fas fa-user-check mr-1"></i>
                                        <p> Daftar Peserta</p>
                                    </a>
                                </li>
                            @endhasanyrole
                        @endhasanyrole
                    </ul>
                </li>


                <?php
                $testActive = request()->routeIs('admin.quiz.index') || request()->routeIs('master.aspect.index') || request()->routeIs('master.question.index') || request()->routeIs('master.typePackage.index') || request()->routeIs('master.package.index') || request()->routeIs('mytest.history') ? 'menu-open' : ''; ?>
                @hasanyrole('admin|package-manager|question-operator|manager')
                    <li class="nav-item {{ $testActive }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-keyboard"></i>
                            <p>
                                Soal & Paket
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @hasanyrole('admin|question-operator|counselor|manager')
                                <li class="nav-item {{ $display }}">
                                    <a href="{{ route('master.aspect.index') }}"
                                        class="nav-link {{ request()->routeIs('master.aspect.index') ? 'bg-primary' : '' }}">
                                        <i class="nav-icon fas fa-cubes"></i>
                                        <p>
                                            Aspek Pertanyaan
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item {{ $display }}">
                                    <a href="{{ route('master.question.index') }}"
                                        class="nav-link {{ request()->routeIs('master.question.index') ? 'bg-primary' : '' }}">
                                        <i class="nav-icon fas fa-clipboard-list"></i>
                                        <p>
                                            Bank Soal
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item {{ $display }}">
                                    <a href="{{ route('admin.quiz.index') }}"
                                        class="nav-link {{ request()->routeIs('admin.quiz.index') ? 'bg-primary' : '' }}">
                                        <i class="nav-icon fas fa-file"></i>
                                        <p>
                                            Daftar Test
                                        </p>
                                    </a>
                                </li>
                            @endhasanyrole

                            @hasanyrole('admin|counselor|manager')
                                <li class="nav-item {{ $display }}">
                                    <a href="{{ route('mytest.history') }}"
                                        class="nav-link {{ request()->routeIs('mytest.history') ? 'bg-primary' : '' }}">
                                        <i class="nav-icon fas fa-book"></i>
                                        <p>
                                            Riwayat Test
                                        </p>
                                    </a>
                                </li>
                            @endhasanyrole


                            @hasanyrole('admin|package-manager|manager')
                                @hasanyrole('admin|manager')
                                    <li class="nav-item {{ $display }}">
                                        <a href="{{ route('master.typePackage.index') }}"
                                            class="nav-link {{ request()->routeIs('master.typePackage.index') ? 'bg-primary' : '' }}">
                                            <i class="nav-icon fas fa-cube"></i>
                                            <p>
                                                Kategori Paket
                                            </p>
                                        </a>
                                    </li>
                                @endhasanyrole
                                <li class="nav-item {{ $display }}">
                                    <a href="{{ route('master.package.index') }}"
                                        class="nav-link {{ request()->routeIs('master.package.index') ? 'bg-primary' : '' }}">
                                        <i class="nav-icon fas fa-gift"></i>
                                        <p>
                                            Daftar Paket
                                        </p>
                                    </a>
                                </li>
                            @endhasanyrole
                        </ul>
                    </li>

                @endhasanyrole

                @hasanyrole('admin|manager')
                    <?php
                    $laporanActive = request()->routeIs('laporan.index') ? 'menu-open' : ''; ?>
                    <li class="nav-item {{ $laporanActive }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>
                                Laporan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item {{ $display }}">
                                <a href="{{ route('laporan.index') }}"
                                    class="nav-link {{ request()->routeIs('laporan.index') ? 'bg-primary' : '' }}">
                                    <i class="nav-icon fas fa-coins"></i>

                                    <p>
                                        Laporan Pendapatan
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="../../index3.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Laporan Hasil</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endhasanyrole

                @hasanyrole('admin|manager')
                    <li class="nav-item {{ $display }}">
                        <a href="{{ route('master.user.index') }}"
                            class="nav-link {{ request()->routeIs('master.user.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>
                                Daftar Pengguna
                            </p>
                        </a>
                    </li>
                @endhasanyrole


                {{-- 
                <li class="nav-item ">
                    <a href="{{ route('contact') }}"
                        class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-phone-square"></i>
                        <p>
                            Contact Person
                        </p>
                    </a>
                </li> --}}

                {{-- @unless (auth()->user()->hasRole('admin')) --}}
                <li class="nav-item {{ $display }}">
                    <a href="{{ route('my-account.show') }}"
                        class="nav-link {{ request()->routeIs('my-account.show') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            My Account
                        </p>
                    </a>
                </li>
                {{-- @endunless --}}

                <li class="nav-item mb-3">
                    <a href="javascript:void(0)" onclick="logoutAndRemoveToken()" class="nav-link ">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>
                            Log Out
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>

    <!-- /.sidebar -->
</aside>

{{-- <li class="nav-item {{ $display }}">
    <a href="{{ route('quiz.listQuiz') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-book"></i>
        <p>
            Daftar Quiz
        </p>
    </a>
</li>
<li class="nav-item {{ $display }}">
    <a href="{{ route('quiz.historyQuiz') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-book"></i>
        <p>
            Riwayat Quiz
        </p>
    </a>
</li> --}}

{{-- <li class="nav-item {{ $display }}">
    <a href="{{ route('master.payment.index') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-credit-card"></i>
        <p>
            Payment Package
        </p>
    </a>
</li> --}}
