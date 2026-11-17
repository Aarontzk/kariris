@extends('layouts.app')
@section('title', 'Bookmark')
@section('nav', 'bookmarks')
@section('body')
@include('partials.profesi-list', ['route' => 'bookmarks', 'title' => 'Bookmark', 'emptyText' => 'Belum ada profesi yang kamu bookmark.'])
@endsection
