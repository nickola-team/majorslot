@extends('frontend.venus.layouts.app', ['logo' => 'goldstar'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop