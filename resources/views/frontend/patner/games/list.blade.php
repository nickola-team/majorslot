@extends('frontend.venus.layouts.app', ['logo' => 'patner'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop