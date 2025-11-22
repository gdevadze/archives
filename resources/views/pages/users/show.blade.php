@extends('layouts.app')


@section('content')
    <div class="breadcrumb">
        <h1>მომხმარებლის დეტალები | </h1>
        <ul>
            <li><a href="{{ url('users/index') }}" >დათვალიერება</a></li>
        </ul>
    </div>
    <div class="separator-breadcrumb border-top"></div>


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>სახელი:</strong>
            {{ $user->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>ელ.ფოსტა :</strong>
            {{ $user->email }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>პრივილეგია:</strong>
            @if(!empty($user->getRoleNames()))
                @foreach($user->getRoleNames() as $v)
                    <label class="badge badge-success">{{ $v }}</label>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
