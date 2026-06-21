@extends('layouts.app')
@section('title', 'Edit Transaksi')

@section('content')
    <form method="POST" action="{{ route('transaksi.update', $transaksi) }}">
        @csrf
        @method('PUT')
        @include('transaksi._form', ['submitLabel' => 'Simpan Perubahan'])
    </form>
@endsection
