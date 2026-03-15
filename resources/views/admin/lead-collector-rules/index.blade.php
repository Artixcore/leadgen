@extends('admin.layouts.app')

@section('title', __('Rules') . ' — ' . $collector->name)

@section('content')
    @include('admin.partials.page-header', [
        'title' => __('Rules') . ' — ' . $collector->name,
        'breadcrumbs' => [
            __('Dashboard') => route('admin.dashboard'),
            __('Lead Sources') => route('admin.lead-sources.index'),
            __('Lead Collectors') => route('admin.lead-collectors.index'),
            $collector->name => route('admin.lead-collectors.show', $collector),
            __('Rules') => null,
        ],
    ])

    @if (session('status'))
        <x-admin.alert type="success" class="mb-4">{{ session('status') }}</x-admin.alert>
    @endif

    <div class="mb-4">
        <a href="{{ route('admin.lead-collectors.show', $collector) }}" class="btn btn-outline-secondary">{{ __('Back to collector') }}</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Add rule') }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.lead-collectors.rules.store', $collector) }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <label for="rule_key" class="form-label">{{ __('Rule key') }}</label>
                    <input type="text" id="rule_key" name="rule_key" class="form-control" value="{{ old('rule_key') }}" placeholder="e.g. has_website" required>
                    @error('rule_key')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label for="rule_operator" class="form-label">{{ __('Operator') }}</label>
                    <select id="rule_operator" name="rule_operator" class="form-select" required>
                        @foreach (['eq', 'neq', 'exists', 'not_exists'] as $op)
                            <option value="{{ $op }}" @selected(old('rule_operator') === $op)>{{ $op }}</option>
                        @endforeach
                    </select>
                    @error('rule_operator')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <label for="rule_value" class="form-label">{{ __('Value') }}</label>
                    <input type="text" id="rule_value" name="rule_value" class="form-control" value="{{ old('rule_value') }}">
                    @error('rule_value')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-1">
                    <label for="score_weight" class="form-label">{{ __('Score') }}</label>
                    <input type="number" id="score_weight" name="score_weight" class="form-control" value="{{ old('score_weight', 0) }}">
                    @error('score_weight')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="form-check mb-2">
                        <input type="checkbox" id="is_required" name="is_required" value="1" class="form-check-input" @checked(old('is_required'))>
                        <label for="is_required" class="form-check-label">{{ __('Required') }}</label>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">{{ __('Add rule') }}</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ __('Rules list') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th>{{ __('Key') }}</th>
                            <th>{{ __('Operator') }}</th>
                            <th>{{ __('Value') }}</th>
                            <th>{{ __('Score weight') }}</th>
                            <th>{{ __('Required') }}</th>
                            <th class="table-action">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rules as $rule)
                            <tr>
                                <td><code>{{ $rule->rule_key }}</code></td>
                                <td>{{ $rule->rule_operator }}</td>
                                <td>{{ $rule->rule_value ?? '—' }}</td>
                                <td>{{ $rule->score_weight }}</td>
                                <td>@if ($rule->is_required)<span class="badge bg-warning text-dark">{{ __('Yes') }}</span>@else—@endif</td>
                                <td class="table-action">
                                    <a href="{{ route('admin.lead-collectors.rules.edit', $rule) }}" class="me-2" title="{{ __('Edit') }}"><i class="align-middle fas fa-fw fa-pen"></i></a>
                                    <form action="{{ route('admin.lead-collectors.rules.destroy', $rule) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('Remove this rule?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link btn-sm p-0 text-danger" title="{{ __('Delete') }}"><i class="fas fa-fw fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">{{ __('No rules yet. Add one above.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
