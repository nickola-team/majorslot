@extends('frontend.poseidon01.layouts.app', ['logo' => 'wontooslot'])
@section('page-title', $title)

@section('content')
@include('frontend.poseidon01.games.list')
@stop