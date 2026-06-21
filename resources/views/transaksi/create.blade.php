@extends('layouts.app')
@section('title', 'Tambah Transaksi')

@section('content')
    <form method="POST" action="{{ route('transaksi.store') }}">
        @csrf
        @include('transaksi._form', ['submitLabel' => 'Simpan & Buat Nota'])
    </form>
@endsection
