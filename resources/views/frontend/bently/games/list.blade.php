@extends('frontend.cinema.layouts.app', ['logo' => 'bently'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop