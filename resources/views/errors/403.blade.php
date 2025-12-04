@extends('errors.base')

@section('error-icon')
    <i class="icofont-ban icofont-5x text-danger"></i>
@endsection

@section('error-code', '403')

@section('error-title', 'Accès Interdit')

@section('error-message', 'Vous n\'avez pas la permission d\'accéder à cette ressource.')
