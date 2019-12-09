@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="">
                            <input type="hidden" name="search" value="true">
                            <div class="form-group row mb-0">
                                <div class="btn-submit-align-46">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Match') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="text-area col-md-8 offset-md-2">
                            @if (!empty($matchUsers))
                                @foreach($matchUsers as $matchUser)
                                    <p> User #{{ $matchUser->oldest_id }} is matched with User #{{ $matchUser->id }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
