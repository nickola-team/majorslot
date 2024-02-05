@extends('frontend.venus.layouts.app', ['logo' => 'daebak'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop