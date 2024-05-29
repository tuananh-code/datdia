<div class="bravo-list-item @if (!$rows->count()) not-found @endif">
    @if ($rows->count())
        <div class="text-paginate">
            <h2 class="text">
                {{-- @dd(serverVN()) --}}
                @if (serverVN())
                    Tìm thấy: {{ ['total' => $rows->total()]['total'] }} kết quả
                @else
                    @if ($rows->total() > 1)
                        {{ __(':count estates found', ['count' => $rows->total()]) }}
                    @else
                        {{ __(':count space found', ['count' => $rows->total()]) }}
                    @endif
                @endif
            </h2>
            <span class="count-string">Từ {{ ['from' => $rows->firstItem()]['from'] }} -
                {{ ['to' => $rows->lastItem()]['to'] }} trên {{ ['total' => $rows->total()]['total'] }} Kết quả</span>
        </div>
        <div class="list-item">
            <div class="row">
                @foreach ($rows as $row)
                    <div class="col-lg-4 col-md-6">
                        @include('Space::frontend.layouts.search.loop-gird')
                    </div>
                @endforeach
            </div>
        </div>
        {{-- @dd($rows->appends(array_merge(request()->query(), ['_ajax' => 1]))->links()) --}}
        <div class="bravo-pagination">
            {{ $rows->appends(array_merge(request()->query(), ['_ajax' => 1]))->links() }}
        </div>
    @else
        <div class="not-found-box">
            <h3 class="n-title">{{ __("We couldn't find any spaces.") }}</h3>
            <p class="p-desc">{{ __('Try changing your filter criteria') }}</p>
            {{-- <a href="#" onclick="return false;" click="" class="btn btn-danger">{{__("Clear Filters")}}</a> --}}
        </div>
    @endif
</div>
