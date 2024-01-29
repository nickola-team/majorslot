@extends('frontend.iris.layouts.app', ['logo' => 'nustar'])
@section('page-title', $title)

@section('content')
@include('frontend.iris.games.list')
@stop