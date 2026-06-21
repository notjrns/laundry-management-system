@extends('layouts.app')
@section('title', 'Edit Layanan')

@section('content')
    <form method="POST" action="{{ route('layanan.update', $layanan) }}">
        @csrf
        @method('PUT')
        @include('layanan._form', ['submitLabel' => 'Simpan Perubahan'])
    </form>
@endsection
