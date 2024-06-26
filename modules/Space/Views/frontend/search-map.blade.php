@extends('layouts.app', ['container_class' => 'container-fluid', 'header_right_menu' => true])
@section('head')
    <link href="{{ asset('dist/frontend/module/space/css/space.css?_ver=' . config('app.version')) }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/ion_rangeslider/css/ion.rangeSlider.min.css') }}" />
    <style type="text/css">
        .bravo_topbar,
        .bravo_footer {
            display: none
        }

        .arrow-down:hover {
            background: green !important;
        }

        .arrow-down:hover::before {
            border-top: 10px solid green !important;
        }

        .arrow-down::before {
            content: '';
            position: absolute;
            top: 60%;
            left: 36%;
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-top: 10px solid #ff0000;
            /* Adjust color as needed */
        }
    </style>
@endsection
@section('content')
    @php
        // $current_path = serverPath();
        $estate_path = estatePath();
    @endphp
    <div class="bravo_search_tour bravo_search_space">
        <h1 class="d-none">
            {{ setting_item_with_lang('space_page_search_title') }}
        </h1>
        @if (!$estate_path)
            <div class="bravo_form_search_map">
                @include('Space::frontend.layouts.search-map.form-search-map')
            </div>
        @endif

        <div class="bravo_search_map {{ setting_item_with_lang('space_layout_map_option', false, 'map_left') }}">
            <div class="results_map">
                <div class="map-loading d-none">
                    <div class="st-loader"></div>
                </div>
                <div id="bravo_results_map" class="results_map_inner search-map-custom"></div>
            </div>
            <div class="results_item">
                @include('Space::frontend.layouts.search-map.advance-filter')
                <div class="listing_items">
                    {{-- Filter result --}}

                    @if ($estate_path)
                        @include('Space::frontend.layouts.search-map.list-estate')
                    @else
                        @include('Space::frontend.layouts.search-map.list-item')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script>
        var bravo_map_data = {
            markers: {!! json_encode($markers) !!}
        };
    </script>
    <script type="text/javascript" src="{{ asset('libs/ion_rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('module/space/js/space-map.js?_ver=' . config('app.version')) }}"></script>
@endsection
