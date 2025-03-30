@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Request a document') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('requests.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="document_type_id" class="col-md-4 col-form-label text-md-right">{{ __('Document type') }}</label>

                            <div class="col-md-6">
                                <select id="document_type_id" class="form-control @error('document_type_id') is-invalid @enderror" name="document_type_id" required>
                                    <option value="">Select a document type</option>
                                    @foreach ($documentTypes as $category => $types)
                                        <optgroup label="{{ $category }}">
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>

                                @error('document_type_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Request') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
