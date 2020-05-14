@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="panel-body">
                        <p>Dados do Usuário:</p>
                        <p>Nome: {{$user->name}}</p>
                        <p>E-mail: {{$user->email}}</p>
                        <p>Github: {{$user->email_github}}</p>
                        <p>Facebook: {{$user->email_facebook}}</p>
                        <br>
                        @if($user->email_github)
                          <a class="btn btn-default disabled">Conectado com o Github</a>
                        @else
                          <a href="{{route('entrargithub')}}" class="btn btn-primary">Conectar com o Github</a>
                        @endif
                        @if($user->email_facebook)
                          <a class="btn btn-default disabled">Conectado com o Facebook</a>
                        @else
                          <a href="{{route('entrarfacebook')}}" class="btn btn-primary">Conectar com o Facebook</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
