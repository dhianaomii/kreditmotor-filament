@extends('be.master')

@section('navbar')
@include('be.navbar')
@endsection

@section('sidebar')
@include('be.sidebar')
@endsection

@section('content')
@livewire('list-jenis-motor')
@endsection

@section('footer')
@include('be.footer')
@endsection
