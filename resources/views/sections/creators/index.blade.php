@extends('indigo-layout::main')

@section('meta_title', _p('psmoduler::pages.creator.meta_title', 'Creator') . ' - ' . config('app.name'))
@section('meta_description', _p('psmoduler::pages.creator.meta_description', 'Module creator  in application'))

@push('head')

@endpush

@section('title')
    {{ _p('psmoduler::pages.creator.headline', 'Creator') }}
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-3 cell--dsm">
            <h4>{{ _p('psmoduler::pages.creator.create_module', 'Create module') }}</h4>
            <div class="card">
                <div class="card-body">
                    <p>{{ _p('psmoduler::pages.creator.description_create_your_module', 'Create your Chrome module') }}</p>
                   <div class="section">
                       <form-builder url="/" send-text="{{ _p('psmoduler::pages.creator.send_text', 'Create') }}"
                                     @send="(data) => {AWEMA._store.commit('setData', {param: 'createModule', data: data}); AWEMA.emit('modal::create_module_confirm:open')}"
                                     disabled-dialog>
                           <fb-input name="name_module" label="{{ _p('psmoduler::pages.creator.name_module', 'Name module') }}"
                           hint=""></fb-input>
                           <small class="text-caption">{{ _p('psmoduler::pages.creator.hint_only_letters', 'You can only use letters') }}</small>
                          <div class="mt-20">
                              <fb-switcher name="with_package" label="{{_p('psmoduler::pages.creator.create_module_with_package', 'Create an module with the Laravel package.')}}"></fb-switcher>
                          </div>
                       </form-builder>
                   </div>
                </div>
            </div>
        </div>
    </div>
    <div class="grid section">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('psmoduler::pages.example.history_modules', 'History modules') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('psmoduler.creator.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="histories_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="created_at" label="{{ _p('psmoduler::pages.creator.created_at', 'Created at') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.created_at }}
                                    </template>
                                </tb-column>
                                <tb-column name="name" label="{{ _p('psmoduler::pages.creator.name', 'Name') }}"></tb-column>
                                <tb-column name="with_package" label="{{ _p('psmoduler::pages.creator.with_package', 'Created at') }}">
                                    <template slot-scope="col">
                                       <span v-if="col.data.with_package">
                                           {{ _p('psmoduler::pages.creator.yes', 'Yes') }}
                                       </span>
                                        <span v-else>
                                           {{ _p('psmoduler::pages.creator.no', 'No') }}
                                       </span>
                                    </template>
                                </tb-column>
                            </table-builder>
                            <paginate-builder
                                :meta="table.meta"
                            ></paginate-builder>
                        </template>
                        @include('indigo-layout::components.base.loading')
                        @include('indigo-layout::components.base.empty')
                        @include('indigo-layout::components.base.error')
                    </content-wrapper>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')

    <modal-window name="create_module_confirm" class="modal_formbuilder"
                  title="{{ _p('psmoduler::pages.creator.confirm_create', 'Confirm create') }}">
        <form-builder :edited="true" url="{{ route('psmoduler.creator.store') }}"
                      @sended="AWEMA.emit('content::histories_table:update')"
                      send-text="{{ _p('psmoduler::pages.creator.confirm', 'Confirm') }}" store-data="createModule"
                      disabled-dialog>
            <fb-input name="name_module" label="{{ _p('psmoduler::pages.creator.name_module', 'Name module') }}"></fb-input>
            <div class="mt-20">
                <fb-switcher name="with_package" label="{{_p('psmoduler::pages.creator.create_module_with_package', 'Create an module with the Laravel package.')}}"></fb-switcher>
            </div>
        </form-builder>
    </modal-window>
@endsection
