@extends('frontend.cinema.layouts.app', ['logo' => 'hera','hotel' => 'disabled'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop