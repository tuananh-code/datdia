@php
    $current_path = serverPath();
    $id = $rows[0]->create_user;
    if ($id) {
        $results = DB::select('select * from users where id = ?', [$id]);
        $name = $results[0]->business_name;
        $phone = $results[0]->phone;
        $mail = $results[0]->email;
    }
@endphp
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
            <style>
                @media only screen and (max-width: 600px) {
                    .col-mobile {
                        max-width: 25% !important;
                        flex: 0 0 25%;
                    }
                }
            </style>
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="col-2 p-2 col-mobile">
                        <img width="100%"
                            src="https://static-00.iconduck.com/assets.00/avatar-default-icon-512x506-865e9t94.png"
                            alt="">
                    </div>
                    <div class="text-left">
                        @if ($id)
                            <h4 class="">{{ $name }}</h4>
                            @if ($phone)
                                <div>
                                    <h4 class="">SĐT: {{ $phone }}</h4>
                                </div>
                            @else
                                <div>
                                    <h4 class="">Mail: {{ $mail }}</h4>
                                </div>
                            @endif
                        @else
                            <h4 class="">{{ $rows[0]->contact_name }}</h4>
                            <div>
                                <h4 class="">SĐT: {{ $rows[0]->contact }}</h4>
                            </div>
                        @endif
                        <div>
                            <h4 class="">Địa chỉ: {{ $row->name }}</h4>
                        </div>
                    </div>
                </div>
                <div>
                    @if (setting_item('space_enable_review'))
                        <?php
                        $reviewData = $rows[0]->getScoreReview();
                        $score_total = $reviewData['score_total'];
                        ?>
                        <div class="service-review d-flex flex-column align-items-center">
                            <span class="rate">
                                @if ($reviewData['total_review'] > 0)
                                    {{ $score_total }}/5
                                @endif
                                <span class="rate-text text-primary">{{ $reviewData['review_text'] }}</span>
                            </span>
                            <span class="review">
                                @if ($reviewData['total_review'] > 1)
                                    @if ($reviewData['total_review'] > 1)
                                        {{ __(':number Reviews', ['number' => $reviewData['total_review']]) }}
                                    @else
                                        {{ __(':number Review', ['number' => $reviewData['total_review']]) }}
                                    @endif
                                @else
                                    {{ __(':number Review') }}: 0

                                @endif
                            </span>
                        </div>
                    @endif
                </div>
            </div>
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
