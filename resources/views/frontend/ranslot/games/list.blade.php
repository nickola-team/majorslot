@extends('frontend.venus.layouts.app', ['logo' => 'ranslot'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop