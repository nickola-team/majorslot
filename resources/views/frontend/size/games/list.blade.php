@extends('frontend.cinema.layouts.app', ['logo' => 'size'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop