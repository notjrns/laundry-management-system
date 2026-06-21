@extends('layouts.app')
@section('title', 'Tambah Rak')

@section('content')
    <form method="POST" action="{{ route('rak.store') }}">
        @csrf
        @include('rak._form', ['submitLabel' => 'Buat Rak'])
    </form>
@endsection
