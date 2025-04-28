@extends('be.master')

@section('navbar')
@include('be.navbar')
@endsection

@section('sidebar')
@include('be.sidebar')
@endsection

@section('content')
@livewire('list-user')
@endsection

@section('footer')
@include('be.footer')
@endsection
