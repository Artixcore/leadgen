@extends('admin.layouts.app')

@section('title', __('Edit rule') . ' — ' . $collector->name)

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Edit rule'),
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Lead Sources') => route('admin.lead-sources.index'),
            __('Lead Collectors') => route('admin.lead-collectors.index'),
            $collector->name => route('admin.lead-collectors.show', $collector),
            __('Rules') => route('admin.lead-collectors.rules.index', $collector),
            __('Edit') => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="mb-4">
        <a href="{{ route('admin.lead-collectors.rules.index', $collector) }}" class="btn btn-outline-secondary">{{ __('Back to rules') }}</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Edit rule') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.lead-collectors.rules.update', $rule) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="rule_key" class="form-label">{{ __('Rule key') }}</label>
                        <input type="text" id="rule_key" name="rule_key" class="form-control" value="{{ old('rule_key', $rule->rule_key) }}" required>
                        @error('rule_key')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="rule_operator" class="form-label">{{ __('Operator') }}</label>
                        <select id="rule_operator" name="rule_operator" class="form-select" required>
                            @foreach (['eq', 'neq', 'exists', 'not_exists'] as $op)
                                <option value="{{ $op }}" @selected(old('rule_operator', $rule->rule_operator) === $op)>{{ $op }}</option>
                            @endforeach
                        </select>
                        @error('rule_operator')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="rule_value" class="form-label">{{ __('Value') }}</label>
                        <input type="text" id="rule_value" name="rule_value" class="form-control" value="{{ old('rule_value', $rule->rule_value) }}">
                        @error('rule_value')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="score_weight" class="form-label">{{ __('Score weight') }}</label>
                        <input type="number" id="score_weight" name="score_weight" class="form-control" value="{{ old('score_weight', $rule->score_weight) }}">
                        @error('score_weight')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check">
                            <input type="checkbox" id="is_required" name="is_required" value="1" class="form-check-input" @checked(old('is_required', $rule->is_required))>
                            <label for="is_required" class="form-check-label">{{ __('Required') }}</label>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">{{ __('Update rule') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
