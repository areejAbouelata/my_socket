@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                    <div class="card-header">{{ __('create post') }}</div>
                    <div class="card-body">
                        <form action="{{route('store.post')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="txt" class="col-2">{{__('Body')}}</label>
                                <textarea name="body" id="" cols="60" rows="5" id="txt"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="photo" class="col-2">{{__('photo')}}</label>
                                <input type="file" name="photo" id="photo">
                            </div>

                            <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
