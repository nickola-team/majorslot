@extends('frontend.venus.layouts.app', ['logo' => 'top02'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop