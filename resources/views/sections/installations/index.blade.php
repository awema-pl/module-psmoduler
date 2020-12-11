@extends('indigo-layout::installation')

@section('meta_title', _p('psmoduler::pages.installation.meta_title', 'Installation psmoduler') . ' - ' . config('app.name'))
@section('meta_description', _p('psmoduler::pages.installation.meta_description', 'Installation psmoduler in application'))

@push('head')

@endpush

@section('title')
    <h2>{{ _p('psmoduler::pages.installation.headline', 'Installation psmoduler') }}</h2>
@endsection

@section('content')
    <form-builder disabled-dialog="" url="{{ route('psmoduler.installation.index') }}" send-text="{{ _p('psmoduler::pages.installation.send_text', 'Install') }}"
    edited>
        <div class="section">
            <ul>
                <li>
                    {{ _p('psmoduler::pages.installation.will_be_execute_migrations', 'Will be execute package migrations') }}
                </li>
            </ul>
        </div>
    </form-builder>
@endsection
