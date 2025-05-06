<div class="container mt-2">
    <div class="row">
        <div class="col-12">

            {{-- Блок для вывода ошибок валидации --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="list-unstyled mb-0"> {{-- mb-0 убирает нижний отступ у списка --}}
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Блок для вывода сообщения об успехе (флеш-сообщение) --}}
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

        </div> {{-- /.col-12 --}}
    </div> {{-- /.row --}}
</div> {{-- /.container --}}