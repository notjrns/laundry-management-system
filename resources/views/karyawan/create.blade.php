@extends('layouts.app')
@section('title', 'Tambah Karyawan')

@section('content')
    <form method="POST" action="{{ route('karyawan.store') }}">
        @csrf
        @include('karyawan._form', ['submitLabel' => 'Simpan'])
    </form>
@endsection
