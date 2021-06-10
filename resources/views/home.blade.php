@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>

                        @endif

                        <a href="{{route('create.post')}}" class="btn btn-primary ml-1 mb-3 col-3" target="_blank">
                            {{__('add post')}}
                        </a>

                        {{--for loop all posts--}}
                        @foreach($posts as $post)
                            <div class="card">
                                <div class="card-header">
                                    {{$post->user->name}}
                                </div>
                                <div class="card-body">
                                    <blockquote class="blockquote mb-0">
                                        @if($post->files()->first() && \Illuminate\Support\Facades\Storage::exists('public/images/posts/'.$post->files()->first()->file))
                                            <img class="card-img-top"
                                                 src="{{asset(\Illuminate\Support\Facades\Storage::url('/images/posts/'.$post->files()->first()->file))}}"
                                                 alt="Card image cap">
                                        @endif
                                        <p>{!! $post->body !!}
                                        </p>
                                        <footer class="blockquote-footer all_comments">
                                            <div class="alert alert-dark">
                                                <span class="badge badge-primary"
                                                      id="post_{{$post->id}}">{{$post->likes()->count()}}</span>
                                                <button class=" col-md-2 d-inline btn  @if(!auth()->user()->post_like($post->id))btn-gray @else btn-primary @endif like"
                                                        data-id="{{$post->id}}">
                                                    <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                                </button>
                                                <form action="{{route('comment.create')}}" class="comment d-inline">
                                                    @csrf
                                                    <div class="form-group  d-inline">
                                                        <input type="text" class="form-control  d-inline"
                                                               style="width: 60%" id="exampleFormControlInput1"
                                                               placeholder="comment" name="comment">
                                                        <input type="hidden" name="post_id" value="{{$post->id}}"
                                                               hidden>
                                                    </div>
                                                    <button type="submit"
                                                            class="btn btn-primary">{{__('save')}} </button>
                                                </form>
                                            </div>
                                            @foreach($post->comments as $comment)
                                                <div class="alert alert-dark">
                                                    <div class="form-group  d-inline"><input type="text"
                                                                                             id="exampleFormControlInput1"
                                                                                             placeholder="{{$comment->body}}"
                                                                                             class="form-control  d-inline"
                                                                                             style="width: 90%;"
                                                                                             disabled></div>
                                                    <span class="badge badge-secondary">{{$comment->user->name}}</span>
                                                </div>
                                            @endforeach
                                        </footer>
                                    </blockquote>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
<script>
    $('body').on('click', '.like', function (e) {
        e.preventDefault();
        let button = $(this);
        let postId = $(this).data('id');
        $.ajax({
            url: '{{route('like.post')}}',
            method: 'post',
            data: {
                '_token': '{{csrf_token()}}',
                'post_id': postId
            },
            error: function () {
            },
            success: function (data) {
                if (data['message'] == 'liked') {
                    button.addClass('btn-primary');
                    $('#post_' + postId).text(parseInt($('#post_' + postId).text()) + 1);
                }
                if (data['message'] == 'un_liked') {
                    button.removeClass('btn-primary');
                    if (parseInt($('#post_' + postId).text()) >= 0) {
                        $('#post_' + postId).text(parseInt($('#post_' + postId).text()) - 1);
                    }
                }
            }
        });

    });
    $('body').on('submit', '.comment', function (e) {
        e.preventDefault();

        let location = $(this);
        let data = new FormData(this);
//        alert(data);
        console.log(data);
        let url = $(this).attr('action');
        console.log(url);
        $.ajax({
            url: url,
            method: 'post',
            data: data,
            processData: false,
            contentType: false,
            error: function () {
            },
            success: function (data) {
                if (data['message'] == 'success') {
                    let element = `<div class="alert alert-dark">
                                                    <div class="form-group  d-inline"><input type="text" id="exampleFormControlInput1" placeholder="` + data['comment']['body'] + `"  class="form-control  d-inline" style="width: 90%;" disabled> </div>
                                                </div>`;
//                    append
                    $(location).parents('.all_comments:first').append(element);
//                    clear form
                    $(location).find('input[name=comment]:first').val("");
                    $(location).find('input[name=comment]:first').attr('placeholder' , 'comment');
                }
            }
        });

    })
</script>
@endpush
