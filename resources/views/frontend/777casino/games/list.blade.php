@extends('frontend.iris.layouts.app', ['logo' => '777casino'])
@section('page-title', $title)

@section('content')
@include('frontend.iris.games.list')
@stop