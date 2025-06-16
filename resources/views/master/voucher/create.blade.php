@extends('layouts.section')
@section('content')
    <div class="px-3 py-4">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="font-weight-bold card-title">Tambah Voucher Paket</h3>
                            </div>

                            <!-- form start -->
                            <form method="post" action="{{ route('master.voucher.store') }}">
                                @csrf
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 control-label text-left">Nama Voucher
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <input class="form-control @error('name') is-invalid @enderror" type="text"
                                                name="name" id="name" value="{{ old('name') }}"
                                                placeholder="Nama Voucher" required>
                                            @error('name')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="package_id" class="col-md-4 control-label text-left">Pilih Paket
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <select class="form-control @error('package_id') is-invalid @enderror"
                                                name="package_id" id="package_id" required>
                                                <option value="">
                                                    Pilih Paket</option>
                                                @foreach ($packages as $package)
                                                    <option value="{{ $package->id }}">
                                                        {{ $package->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('package_id')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 control-label text-left">Tipe Voucher
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <select name="type_voucher" id="type_voucher" class="form-control" required>
                                                <option value="">Pilih Tipe Voucher</option>
                                                <option value="discount">Diskon</option>
                                                <option value="fixed_price">Fixed Price</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="form_discount">
                                        <label for="discount" class="col-md-4 control-label text-left">Diskon
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <div class="input-group ">
                                                <input class="form-control @error('discount') is-invalid @enderror"
                                                    type="number" name="discount" id="discount"
                                                    value="{{ old('discount') }}" placeholder="Diskon">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                                @error('discount')
                                                    <div class="alert alert-danger mt-2">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row" id="form_fixed_price">
                                        <label for="fixed_price" class="col-md-4 control-label text-left">Fixed Price
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <input class="form-control @error('fixed_price') is-invalid @enderror"
                                                type="number" name="fixed_price" id="fixed_price"
                                                value="{{ old('fixed_price') }}" placeholder="Fixed Price">
                                            @error('fixed_price')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="voucher_price" class="col-md-4 control-label text-left">Harga Voucher
                                            <span class="text-danger ml-1">*</span>
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <input class="form-control @error('voucher_price') is-invalid @enderror"
                                                type="number" name="voucher_price" id="voucher_price"
                                                value="{{ old('voucher_price') }}" placeholder="Harga Voucher" required>
                                            @error('voucher_price')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="description" class="col-md-4 control-label text-left">Deskripsi
                                        </label>
                                        <div class="col-md-8 col-sm-12">
                                            <textarea id="quiz_summernote" name="description" id="description" class="form-control summernote">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="alert alert-danger mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="pt-3 d-flex">
                                        <a href="{{ route('master.voucher.index') }}" class="btn btn-danger mr-2">
                                            Back</a>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('javascript-bottom')
        <script>
            $('#package_id').select2();
            document.addEventListener('DOMContentLoaded', function() {
                const typeVoucherSelect = document.getElementById('type_voucher');
                const formDiscount = document.getElementById('form_discount');
                const formFixedPrice = document.getElementById('form_fixed_price');

                function toggleFields() {
                    const selected = typeVoucherSelect.value;

                    if (selected === 'discount') {
                        formDiscount.style.display = 'flex'; // atau 'block'
                        formFixedPrice.style.display = 'none';
                    } else if (selected === 'fixed_price') {
                        formDiscount.style.display = 'none';
                        formFixedPrice.style.display = 'flex';
                    } else {
                        formDiscount.style.display = 'none';
                        formFixedPrice.style.display = 'none';
                    }
                }

                // Initial toggle on page load
                toggleFields();

                // Toggle on change
                typeVoucherSelect.addEventListener('change', toggleFields);
            });
        </script>

        @include('js.master.voucher.script')
    @endpush
@endsection
