@extends('frontend.poseidon01.layouts.app', ['logo' => 'esse'])
@section('page-title', $title)

@section('content')
@include('frontend.poseidon01.games.list')
@stop