@php

$iduser = auth()->user()->id;
$entri = request('entri', 10);
$currentPage = request('page', 1);
$offset = ($currentPage - 1) * $entri;
$cart = collect(session($iduser . '_cart', []));
$idbarang_in_cart = $cart->map(function ($el) {
    return $el['id'];
});
// dd($idbarang_in_cart);
@endphp

@extends('layout')

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- {{dd($params)}} --}}
    <h3 class="mb-3">Data Barang</h3>

    <a href="{{ url('admin/penjualan') }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Kembali</a>
    {{-- <form action="{{ url('') }}/admin/barang/exportpdf" class="d-inline" method="POST">
        @csrf
        <input type="hidden" name="q" value="{{ request('q') }}">
        <button type="submit" class="btn btn-warning"><i class="fa fa-print"></i> Export PDF</button>
    </form>
    <form action="{{ url('') }}/admin/barang/exportexcel" class="d-inline" method="POST">
        @csrf
        <input type="hidden" name="q" value="{{ request('q') }}">
        <button type="submit" class="btn btn-warning"><i class="fa fa-file-excel"></i> Export Excel</button>
    </form> --}}

    <div class="datatable-wrapper shadow-lg rounded mt-4">
        <div class="datatable-heading p-4 border-bottom">
            <b>Data Barang</b>
        </div>

        <div class="datatable-content p-4">
            <div class="datatable-search-wrap d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <p class="mb-0">Menampilkan</p>

                    <form action="<?= url('') ?>/admin/penjualan/pilihbarang" class="mx-2">
                        <input type="hidden" name="page" value="{{ request('page') }}">
                        <input type="hidden" name="entri" value="{{ request('q') }}">
                        <select onchange="this.form.submit()" name="entri" id="" class="form-control py-0 px-2">
                            <option value="10" {{ $entri == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $entri == 25 ? 'selected' : '' }}>25</option>
                        </select>
                    </form>

                    <p class="mb-0">entri</p>
                </div>
                <div class="d-flex align-items-center">
                    <p class="mb-0 mr-2">Pencarian: </p>
                    <form action="<?= url('') ?>/admin/penjualan/pilihbarang" method="get">
                        <input type="hidden" name="page" value="1">
                        <input type="hidden" name="entri" value="{{ request('entri') }}">
                        <input type="text" name="q" id="" class="form-control" value="{{ request('q') }}" />
                    </form>
                </div>
            </div>

            <table class="table mt-4">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Stok</th>
                        <th scope="col">Pilih</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $i => $barang)
                        @php
                            $checked = '';
                            if ($idbarang_in_cart->contains($barang['id'])) {
                                $checked = 'checked';
                            }
                        @endphp
                        <tr>
                            <th scope="row">{{ $offset + $i + 1 }}</th>
                            <td>{{ $barang['kategori'] }}</td>
                            <td>{{ $barang['nama'] }}</td>
                            <td>{{ number_format($barang['harga']) }}</td>
                            <td>{{ $barang['stock'] }}</td>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input position-static centang" type="checkbox"
                                        id="blankCheckbox" value="{{ json_encode($barang) }}" aria-label="..."
                                        {{ $checked }}>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $data->links() }}
        </div>

    </div>

    <script>
        $(".centang").change(function(e) {
            let value = JSON.parse($(this).val())
            // console.log("value", value)
            let url = ""
            if (this.checked) {
                // Set ke session
                url = "{{ url('') . '/admin/penjualan/centang' }}"
            } else {
                // Hapus dari session
                url = "{{ url('') . '/admin/penjualan/uncentang' }}"
            }
            NProgress.start()
            $.ajax({
                type: "POST",
                url,
                data: ({
                    '_token': "{{ csrf_token() }}",
                    ...value
                }),
                dataType: "JSON",
                contentType: 'application/x-www-form-urlencoded; charset=utf-8',
                success: function(res) {
                    NProgress.done()
                    console.log('success centang/uncentang', res)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    NProgress.done()
                    console.log('ERROR centang/uncentang', {
                        resText: jqXHR.responseText,
                        textStatus,
                        errorThrown
                    })
                }
            });
        })
    </script>
    {{-- {{ dd($data) }} --}}
@endsection
