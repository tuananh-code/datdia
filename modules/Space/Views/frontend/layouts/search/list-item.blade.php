<div class="row">
    <div class="col-lg-3 col-md-12">
        @include('Space::frontend.layouts.search.filter-search')
    </div>
    <div class="col-lg-9 col-md-12">
        <div class="bravo-list-item">
            <div class="topbar-search">
                <h2 class="text">
                    @if ($rows->total() > 1)
                        Tìm thấy {{ ['count' => $rows->total()]['count'] }} kết quả
                        {{-- {{ __(":count spaces found",['count'=>$rows->total()]) }}  --}}
                    @else
                        {{ __(':count space found', ['count' => $rows->total()]) }}
                    @endif
                </h2>
                <div class="control">
                    @include('Space::frontend.layouts.search.orderby')
                </div>
            </div>
            <div class="list-item">
                <div class="row">
                    @if ($rows->total() > 0)
                        @foreach ($rows as $row)
                            <div class="col-lg-4 col-md-6">
                                @include('Space::frontend.layouts.search.loop-gird')
                            </div>
                        @endforeach
                    @else
                        <div class="col-lg-12">
                            {{ __('Space not found') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="bravo-pagination">
                {{ $rows->appends(request()->query())->links() }}
                @if ($rows->total() > 0)
                @php 
                // dd(['from' => $rows->firstItem()]['from']);
                @endphp
                    <span
                        class="count-string">Từ {{['from' => $rows->firstItem()]['from']}} - {{['to' => $rows->lastItem()]['to']}} trên {{['total' => $rows->total()]['total']}} Kết quả</span>

                    {{-- <span
                        class="count-string">{{ __('Showing :from - :to of :total Spaces', ['from' => $rows->firstItem(), 'to' => $rows->lastItem(), 'total' => $rows->total()]) }}</span> --}}
                @endif
            </div>
        </div>
    </div>
</div>
