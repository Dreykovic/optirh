@extends('errors.base')

@section('error-icon')
    <i class="icofont-clock-time icofont-5x text-warning"></i>
@endsection

@section('error-code', '419')

@section('error-title', 'Session Expirée')

@section('error-message', 'Votre session a expiré. Veuillez rafraîchir la page et réessayer.')

@section('error-action')
    <a href="javascript:location.reload()" class="btn btn-outline-light">
        <i class="icofont-refresh me-2"></i>Rafraîchir la page
    </a>
@endsection
