<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('dist/adminlte/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Quiz App</span>
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
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('mytest.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            @hasrole('user')
                                My Test
                            @else
                                Riwayat Test
                            @endhasrole
                        </p>
                    </a>
                </li>

                @hasrole('user')
                    <li class="nav-item">
                        <a href="{{ route('myclass.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-chalkboard"></i>
                            <p>
                                My Class
                            </p>
                        </a>
                    </li>
                @endhasrole

                @hasanyrole('admin|user')
                    <li class="nav-item">
                        <?php
                        $orderIds = App\Models\Order::whereNull('deleted_at')
                            ->where('user_id', Auth::user()->id)
                            ->where('status', 1)
                            ->pluck('id');
                        $orderPackage = App\Models\OrderPackage::whereIn('order_id', $orderIds)->whereNull('deleted_at')->count();
                        $orderList = App\Models\Order::whereNull('deleted_at')->where('status', 10)->count();
                        ?>
                        <a href="{{ route('order.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>
                                @hasrole('admin')
                                    Daftar Order <span
                                        class="badge badge-info ml-1 position-absolute">{{ $orderList > 0 ? $orderList : '' }}</span>
                                @else
                                    My Order <span
                                        class="badge badge-info ml-1 position-absolute">{{ $orderPackage > 0 ? $orderPackage : '' }}</span>
                                @endhasrole
                            </p>
                        </a>
                    </li>
                @endhasanyrole


                @hasrole('counselor')
                    <li class="nav-item">
                        <a href="{{ route('class.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-chalkboard"></i>
                            <p>
                                My Class
                            </p>
                        </a>
                    </li>
                @endhasrole

                @hasrole('admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.quiz.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-file"></i>
                            <p>
                                Daftar Tes
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('master.aspect.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-cubes"></i>
                            <p>
                                Aspek Pertanyaan
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('master.question.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>
                                Bank Soal
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('master.package.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-gift"></i>
                            <p>
                                Daftar Paket
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('master.user.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>
                                Daftar Pengguna
                            </p>
                        </a>
                    </li>
                @endhasrole
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link">
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

{{-- <li class="nav-item">
    <a href="{{ route('quiz.listQuiz') }}" class="nav-link">
        <i class="nav-icon fas fa-book"></i>
        <p>
            Daftar Quiz
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('quiz.historyQuiz') }}" class="nav-link">
        <i class="nav-icon fas fa-book"></i>
        <p>
            Riwayat Quiz
        </p>
    </a>
</li> --}}

{{-- <li class="nav-item">
    <a href="{{ route('master.payment.index') }}" class="nav-link">
        <i class="nav-icon fas fa-credit-card"></i>
        <p>
            Payment Package
        </p>
    </a>
</li> --}}
