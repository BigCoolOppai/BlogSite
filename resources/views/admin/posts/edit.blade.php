@extends('admin.layouts.layout')

@section('content')
    <!-- Content Header (Page header) -->
     <div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Редактирование статьи</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                     
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                      
                        <li class="breadcrumb-item active">Блонк пойдж</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    {{-- Main content --}}
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Статья "{{ $post->title }}"</h3>
                    </div>
                    {{-- /.card-header --}}

                    <form role="form" method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            {{-- Title --}}
                            <div class="form-group">
                                <label for="title">Название</label>
                                <input type="text" name="title" id="title"
                                       class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $post->title) }}">
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="form-group">
                                <label for="description">Цитата</label>
                                <textarea name="description" id="description"
                                          class="form-control @error('description') is-invalid @enderror"
                                          rows="3">{{ old('description', $post->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Content --}}
                            <div class="form-group">
                                <label for="content">Контент</label>
                                <textarea name="content" id="content"
                                          class="form-control @error('content') is-invalid @enderror"
                                          rows="7">{{ old('content', $post->content) }}</textarea>
                                @error('content')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Category --}}
                            <div class="form-group">
                                <label for="category_id">Категория</label>
                                <select name="category_id" id="category_id"
                                        class="form-control @error('category_id') is-invalid @enderror">
                                    @foreach($categories as $k => $v)
                                        <option value="{{ $k }}" @selected(old('category_id', $post->category_id) == $k)>
                                            {{ $v }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Tags (assuming $tags is an array like [id => name] or collection) --}}
                            <div class="form-group">
                                <label for="tags">Теги</label>
                                <select name="tags[]" id="tags" class="select2 @error('tags') is-invalid @enderror"
                                        multiple="multiple" data-placeholder="Выбор тегов" style="width: 100%;">
                                    @php
                                        // Ensure $post->tags is a collection, pluck IDs, then convert to array
                                        // For 'old' input, expect an array of IDs
                                        $selectedTags = old('tags', $post->tags ? $post->tags->pluck('id')->toArray() : []);
                                    @endphp
                                    @foreach($tags as $k => $v)
                                        <option value="{{ $k }}" @selected(in_array($k, $selectedTags))>
                                            {{ $v }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tags') {{-- Or tags.* for array validation --}}
                                    <span class="invalid-feedback d-block" role="alert"> {{-- d-block for select2 potentially --}}
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            {{-- Thumbnail --}}
                            <div class="form-group">
                                <label for="thumbnail">Изображение</label>
                                <div class="custom-file">
                                    <input type="file" name="thumbnail"
                                           class="custom-file-input @error('thumbnail') is-invalid @enderror"
                                           id="thumbnail">
                                    <label class="custom-file-label" for="thumbnail">Выберите файл</label>
                                </div>
                                @error('thumbnail')
                                    <span class="invalid-feedback d-block" role="alert"> {{-- d-block to ensure visibility --}}
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                @if($post->getImage())
                                    <img src="{{ $post->getImage() }}" alt="Текущее изображение" class="img-thumbnail mt-2" width="200">
                                @endif
                            </div>

                        </div>
                        {{-- /.card-body --}}

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                    </form>

                </div>
                {{-- /.card --}}
            </div>
            {{-- /.col --}}
        </div>
        {{-- /.row --}}
    </div><!-- /.container-fluid -->
</section>
{{-- /.content --}}
@endsection