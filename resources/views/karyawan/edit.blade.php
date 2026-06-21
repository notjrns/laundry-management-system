@extends('layouts.app')
@section('title', 'Edit Karyawan')

@section('content')
    <form method="POST" action="{{ route('karyawan.update', $karyawan) }}">
        @csrf
        @method('PUT')
        @include('karyawan._form', ['submitLabel' => 'Simpan Perubahan'])
    </form>
@endsection
