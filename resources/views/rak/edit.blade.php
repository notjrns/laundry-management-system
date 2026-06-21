@extends('layouts.app')
@section('title', 'Edit Rak')

@section('content')
    <form method="POST" action="{{ route('rak.update', $rak) }}">
        @csrf
        @method('PUT')
        @include('rak._form', ['submitLabel' => 'Simpan Perubahan'])
    </form>
@endsection
