@extends('frontend.poseidon01.layouts.app', ['logo' => 'sinnolbu01', 'hotel'=>'disabled'])
@section('page-title', $title)

@section('content')
@include('frontend.poseidon01.games.list')
@stop