@extends('frontend.cinema.layouts.app', ['logo' => 'victory'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop