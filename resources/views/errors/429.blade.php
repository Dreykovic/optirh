@extends('errors.base')

@section('error-icon')
    <i class="icofont-speed-meter icofont-5x text-warning"></i>
@endsection

@section('error-code', '429')

@section('error-title', 'Trop de Requêtes')

@section('error-message', 'Vous avez effectué trop de requêtes. Veuillez patienter quelques instants avant de réessayer.')
