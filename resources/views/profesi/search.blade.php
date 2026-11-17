@extends('layouts.app')
@section('title', 'Search')
@section('nav', 'search')
@section('body')
@include('partials.profesi-list', ['route' => 'search', 'title' => 'Search Menu', 'emptyText' => 'Profesi tidak ditemukan. Coba kata kunci lain.'])
@endsection
