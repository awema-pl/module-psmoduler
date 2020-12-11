@extends('indigo-layout::main')

@section('meta_title', _p('psmoduler::pages.example.meta_title', 'Example') . ' - ' . config('app.name'))
@section('meta_description', _p('psmoduler::pages.example.meta_description', 'Example for application'))

@push('head')

@endpush

@section('title')
    {{ _p('psmoduler::pages.example.headline', 'Example') }}
@endsection

@section('pagemap')
    <template #before>
        <h3> {{ _p('psmoduler::pages.example.page_navigation', 'Page navigation') }}</h3>
        <hr class="mb-15">
    </template>
    <template #after>
        <hr class="mb-15">
        <ul class="uk-nav">
            <li>
                <a href="{{url('/')}}" target="blank">Example link 1</a>
            </li>
            <li>
                <a href="{{url('/')}}" target="blank">Example link 2</a>
            </li>
        </ul>
    </template>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-2 cell--dsm" id="vt-java-script">
            <h4>{{ _p('psmoduler::pages.example.java_script', 'Java Script') }}</h4>
            <div class="card">
                <div class="card-body">
                    <psmoduler></psmoduler>
                </div>
            </div>
        </div>
        <div class="cell-1-2 cell--dsm" id="vt-chart">
            <h4>{{ _p('psmoduler::pages.example.chart', 'Chart') }}</h4>
            <div class="card">
                <div class="card-body">
                    <chart-builder
                        :data="{
        datasets: [{
            data: [70, 80, 73, 75, 74, 100, 90, 100, 110, 60],
            backgroundColor: '#b8dfb7',
            borderColor: '#b8dfb7'
        }],
        labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
    }"
                        :options="{
        elements: {
            line: {
                tension: 0
            },
            point: {
                radius: 0
            }
        },
        legend: {
            display: false
        },
        scales: {
            xAxes: [
                {
                    display: false
                }
            ],
            yAxes: [
                {
                    display: false,
                    ticks: {
                        beginAtZero: true
                    }
                }
            ]
        },
        maintainAspectRatio: false,
    }"></chart-builder>
                </div>
            </div>
        </div>
    </div>
    <div class="grid section">
        <div class="cell-1-2 cell--dsm" id="vt-filter">
            <h4>{{ _p('psmoduler::pages.example.filter', 'Filter') }}</h4>
            <div class="card">
                <div class="card-body">
                    <filter-wrapper name="example">
                        <fb-input name="text" label="Text"></fb-input>
                        <fb-select name="array" label="Array"
                                   :select-options="[{name: 'Option 1', value: 'option_one'}, {name: 'Option 2', value: 'option_two'}]"></fb-select>
                    </filter-wrapper>
                </div>
            </div>
        </div>
        <div class="cell-1-2 cell--dsm" id="vt-modal-window">
            <h4>{{ _p('psmoduler::pages.example.modal_window', 'Modal window') }}</h4>
            <div class="card">
                <div class="card-body">
                    <modal-window ref="modal">
                        Text in the modal window
                    </modal-window>

                    <!-- direct call of method for opening a modal window (not recommended) -->
                    <button class="btn" @click="$refs.modal.open()">Open a window</button>
                </div>
            </div>
        </div>
    </div>
    <div class="grid section">
        <div class="cell-1-1 cell--dsm" id="vt-table">
            <h4>{{ _p('psmoduler::pages.example.table', 'Table') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper
{{--                        :url="$url.urlFromOnlyQuery('{{ route('')}}', ['page', 'limit'], $route.query)"--}}

                        :default="[
        {name:'First', email:'first@mail.com'},
        {name: 'Second', email: 'second@mail.com'}
    ]">
                        <table-builder slot-scope="table" :default="table.data">
                            <tb-column name="name" label="Super Name"></tb-column>

                            <!-- visible only on the desktop -->
                            <tb-column name="email" media="desktop">
                                <template slot-scope="col">
                                    <a :href="'mailto:' + col.data.email">@{{ col.data.email }}</a>
                                </template>
                            </tb-column>
                        </table-builder>
{{--                        <paginate-builder--}}
{{--                            :meta="table.meta"--}}
{{--                        ></paginate-builder>--}}
                    </content-wrapper>
                </div>
            </div>
        </div>
    </div>
    <div class="grid section">
        <div class="cell-1-2 cell--dsm" id="vt-tab">
            <h4>{{ _p('psmoduler::pages.example.tab', 'Tab') }}</h4>
            <div class="card">
                <div class="card-body">
                    <tab-builder>
                        <tb-tab label="Tab 1">
                            Tab 1 content
                        </tb-tab>
                        <tb-tab label="Tab 2">
                            <p>Tab 2 content</p>
                        </tb-tab>
                        <tb-link label="Link" url="{{url('/')}}"></tb-link>
                    </tab-builder>
                </div>
            </div>
        </div>
        <div class="cell-1-2 cell--dsm">
            <h4>{{ _p('psmoduler::pages.example.virtual_tour', 'Virtual tour') }}</h4>
            <div class="card">
                <div class="card-body">


                        <virtual-tour name="welcome" @if(\AwemaPL\VirtualTour\Tour::isFromBeginning()) from-beginning @endif :steps="[
                                    {
                                        el: '#vt-java-script',
                                        message: 'Test message. Lorem ipsum, dolor sit amet consectetur adipisicing elit. Velit doloribus ab molestiae vero ratione eum quia fuga assumenda laborum maxime eius, commodi officiis delectus aspernatur ducimus explicabo perspiciatis error magnam ea nemo. Possimus corrupti veritatis laboriosam similique hic officiis ducimus, amet magnam suscipit esse culpa'
                                    },
                                    {
                                        el: '#vt-chart',
                                        fade: false,
                                        message: 'Test message. Lorem ipsum, dolor sit amet consectetur adipisicing elit. Velit doloribus ab molestiae vero ratione eum quia fuga assumenda laborum maxime eius, commodi officiis delectus aspernatur ducimus explicabo perspiciatis error magnam ea nemo. Possimus corrupti veritatis laboriosam similique hic officiis ducimus, amet magnam suscipit esse culpa'
                                    },
                                    {
                                        el: '#vt-filter',
                                        message: 'Test message 2'
                                    },
                                    {
                                        position: 'right',
                                        message: 'This is a center message!'
                                    }
                                ]"></virtual-tour>

                    {{session('virtual-tour-from-beginning')}}
                        <virtual-tour name="welcome1" @if(\AwemaPL\VirtualTour\Tour::isFromBeginning()) from-beginning @endif :fade="false" :steps="[
                                    {
                                        el: '#vt-modal-window',
                                        message: 'From second tour!',
                                        fade: true
                                    },
                                       {
                                        el: '#vt-table',
                                        message: 'From second tour 2!'
                                    },
                                    {
                                        el: '#vt-tab',
                                        message: 'From second tour too!',
                                        fade: true

                                    }
                                ]"></virtual-tour>

                    <a class="btn" href="{{route('psmoduler.example.virtual_tour_from_beginning')}}">From beginning</a>
                </div>
            </div>
        </div>
    </div>
@endsection
