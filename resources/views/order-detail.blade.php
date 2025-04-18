@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detail Pesanan</span>
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">Kembali ke Daftar</a>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="card-title">Informasi Pesanan</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Kode Transaksi</th>
                                    <td>{{ $transaction->transaction_code }}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $transaction->name }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pesanan</th>
                                    <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Total Harga</th>
                                    <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                </tr>
                                
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title">QR Code</h5>
                            <div class="text-center p-3">
                                {!! QrCode::size(200)->generate($transaction->transaction_code) !!}
                                <div class="mt-2">
                                    <small class="text-muted">Scan untuk verifikasi pesanan</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="card-title">Daftar Item</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Item</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->cart as $index => $cart)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $cart->item->name }}</td>
                                    <td>Rp {{ number_format($cart->item->price, 0, ',', '.') }}</td>
                                    <td>{{ $cart->quantity }}</td>
                                    <td>Rp {{ number_format($cart->item->price * $cart->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Total</td>
                                    <td>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection