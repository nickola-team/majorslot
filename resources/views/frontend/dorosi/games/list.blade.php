@extends('frontend.venus.layouts.app', ['logo' => 'dorosi'])
@section('page-title', $title)

@section('content')
@include('frontend.venus.games.list')
@stop