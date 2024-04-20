@php($current_path = serverPath())

<div class="bravo-list-item @if (!$rows->count()) not-found @endif">
    @if ($rows->count())
        {{-- <div class="text-paginate">
            <h2 class="text">

                @if ($rows->total() > 1)
                    {{ __(':count spaces found', ['count' => $rows->total()]) }}
                @else
                    {{ __(':count space found', ['count' => $rows->total()]) }}
                @endif
            </h2>
            <span class="count-string">Từ {{ ['from' => $rows->firstItem()]['from'] }} -
                {{ ['to' => $rows->lastItem()]['to'] }} trên {{ ['total' => $rows->total()]['total'] }} Kết quả</span>
        </div> --}}
        @php($row = $rows[0]->location->translateOrOrigin(app()->getLocale()))
        <div class="list-item">
            <center class="d-flex align-items-center" style="direction: rtl;">
                <div class="col-1 p-2">
                    <img width="100%"
                        src="https://static-00.iconduck.com/assets.00/avatar-default-icon-512x506-865e9t94.png"
                        alt="">
                </div>
                <div class="">
                    <h4 class="">{{ $rows[0]->contact_name }}</h4>
                    <div>
                        <h4 class="">SĐT: {{ $rows[0]->contact }}</h4>
                    </div>
                    <div>
                        <h4 class="">Địa chỉ: {{ $row->name }}</h4>
                    </div>
                </div>
            </center>
            <div class="row">
                @foreach ($rows as $row)
                    <div class="col-lg-4 col-md-6">
                        @include('Space::frontend.layouts.search.loop-estate')
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bravo-pagination">
            {{-- {{ $rows->appends(array_merge(request()->query(), ['_ajax' => 1]))->links() }} --}}
        </div>
    @else
        <div class="not-found-box">
            <h3 class="n-title">{{ __("We couldn't find any spaces.") }}</h3>
            <p class="p-desc">{{ __('Try changing your filter criteria') }}</p>
            {{-- <a href="#" onclick="return false;" click="" class="btn btn-danger">{{__("Clear Filters")}}</a> --}}
        </div>
    @endif
</div>
