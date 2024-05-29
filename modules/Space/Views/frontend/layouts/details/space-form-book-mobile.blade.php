@php
    $vendor = $row->author;
    $name = $vendor->user_name;
    $phone = $vendor->phone;
    $mail = $vendor->email;
    $convert = convertToUSD($row->location_id);
    $number = number_format(convertToUSD($row->location_id), 10, '.', '');
@endphp
<div class="bravo-more-book-mobile" style="z-index: 1234">
    <div class="container" style="height: 100px">
        <div class="left" style="max-width: 44%">
            <div class="g-price">
                {{-- <div class="prefix">
                    <span class="fr_text">{{__("from")}}</span>
                </div> --}}
                <div class="price">
                    {{-- <span class="label">
                        {{__("from")}}
                    </span> --}}
                    <span class="value">
                        {{-- Get price to word in Vietnamese --}}
                        @if ($row->display_sale_price)
                            <span class="onsale">{{ $row->display_sale_price }}</span>
                            <span class="text-price">
                                {{ $row->display_price }}
                            </span>
                        @else
                            <span class="onsale">{{ $row->display_sale_price }}</span>
                            <span class="text-price">
                                {{ formatNumberToVietnamese($row->price, $row->location_id) }}
                                @if ($convert)
                                    ~ ${{ formatToUSD($row->price * $number) }}
                                @endif
                            </span>
                        @endif
                    </span>
                </div>
            </div>
            @if (setting_item('space_enable_review'))
                <?php
                $reviewData = $row->getScoreReview();
                $score_total = $reviewData['score_total'];
                ?>
                <div class="service-review tour-review-{{ $score_total }}">
                    <div class="list-star">
                        <ul class="booking-item-rating-stars">
                            <li><i class="fa fa-star-o"></i></li>
                            <li><i class="fa fa-star-o"></i></li>
                            <li><i class="fa fa-star-o"></i></li>
                            <li><i class="fa fa-star-o"></i></li>
                            <li><i class="fa fa-star-o"></i></li>
                        </ul>
                        <div class="booking-item-rating-stars-active" style="width: {{ $score_total * 2 * 10 ?? 0 }}%">
                            <ul class="booking-item-rating-stars">
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                                <li><i class="fa fa-star"></i></li>
                            </ul>
                        </div>
                    </div>
                    <span class="review m-0">
                        @if ($reviewData['total_review'] > 1)
                            {{ ['number' => $reviewData['total_review']]['number'] }} Reviews
                        @else
                            {{ ['number' => $reviewData['total_review']]['number'] }} Reviews
                        @endif
                    </span>
                </div>
            @endif
        </div>
        <div class="right mb-2" style="max-width: 56%">
            @if ($row->mail)
                <div class="">
                    <p class="m-0">{{ $row->contact_name }}</p>
                    <p class="m-0">{{ $row->contact }}</p>
                    <p class="m-0">{{ $row->mail }}</p>
                </div>
            @else
                @if ($name)
                    <div class="">
                        <h4>{{ $name }}</h4>
                        <h4>SĐT: {{ $phone }}</h4>
                    </div>
                @else
                    @if ($row->contact_name && $row->contact)
                        <div class="">
                            <h4>{{ $row->contact_name }}</h4>
                            <h4>
                                SĐT: {{ $row->contact }}
                            </h4>
                        </div>
                    @else
                        @if ($row->contact_name)
                            <div class="">
                                <h4>{{ $row->contact_name }}</h4>
                            </div>
                        @endif
                        @if ($row->contact)
                            <div class="">
                                <h4>
                                    SĐT: {{ $row->contact }}
                                </h4>
                            </div>
                        @endif
                    @endif
                @endif
            @endif
        </div>
    </div>
</div>
