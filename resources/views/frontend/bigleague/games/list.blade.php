@extends('frontend.cinema.layouts.app', ['logo' => 'bigleague'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop