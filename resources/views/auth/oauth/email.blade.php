@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Еще чуть чуть</div>
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="panel-body">
                        {!! Form::open(array('route' => 'oAuthSetEmail')) !!}

                            <div class="form-group">
                                <label class="col-md-4 control-label">E-Mail </label>

                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" value="">

                                </div>
                            </div>


                            <div class="form-group">
                                <br>
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-sign-in"></i>Зарегистрироваться
                                    </button>
                             </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection