@extends('frontend.cinema.layouts.app', ['logo' => 'phoenix'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop