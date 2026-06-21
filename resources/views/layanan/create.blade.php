@extends('layouts.app')
@section('title', 'Tambah Layanan')

@section('content')
    <form method="POST" action="{{ route('layanan.store') }}">
        @csrf
        @include('layanan._form', ['submitLabel' => 'Simpan'])
    </form>
@endsection
