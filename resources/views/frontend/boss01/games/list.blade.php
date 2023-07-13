@extends('frontend.venus.layouts.app', ['logo' => 'boss01'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop