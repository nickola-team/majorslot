@extends('frontend.venus.layouts.app', ['logo' => 'orora'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop