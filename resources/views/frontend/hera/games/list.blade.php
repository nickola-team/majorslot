@extends('frontend.cinema.layouts.app', ['logo' => 'hera'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop