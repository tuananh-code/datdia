@php
    $translation = $row->translateOrOrigin(app()->getLocale());
    $current_path = serverPath();
    $estate_path = estatePath();
@endphp
<div class="item-loop {{ $wrap_class ?? '' }}">
    @if ($row->is_featured == '1')
        <div class="featured">
            {{ __('Featured') }}
        </div>
    @endif
    <div class="thumb-image p-0">
        @if ($current_path)
            {{-- @dd($row->getDetailUrl()) --}}
            <div class="carousel-inner h-100">
                {{-- Fix map show slide img --}}
                <div class="carousel-item h-100 active">
                    <a @if (!empty($blank)) target="_blank" @endif
                        href="{{ $row->getDetailUrl($include_param ?? true) }}">
                        <img src="{{ get_file_url_s3($row->image_id) }}" class="img-responsive" alt="Trang thương mại điện tử bất động sản datdia">
                        {{ $row->title }}
                    </a>
                </div>
            </div>
        @else
            @if ($row->image_url)
                @if (!empty($disable_lazyload))
                    <div id="slide-spacing-map-{{ $row->id }}" class="carousel slide h-100" data-interval="false">
                        <div class="carousel-inner h-100">
                            @php
                                $row->gallery = $row->image_id . ',' . $row->gallery;
                            @endphp
                            @foreach (explode(',', $row->gallery) as $key => $img)
                                {{-- Fix map show slide img --}}
                                <div class="carousel-item h-100 @if (!$key) active @endif">
                                    <img src="{{ get_file_url_s3($img, 'medium') }}" class="img-responsive"
                                        alt="car">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev carousel-control" type="button"
                            data-target="#slide-spacing-map-{{ $row->id }}" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </button>
                        <button class="carousel-control-next carousel-control" type="button"
                            data-target="#slide-spacing-map-{{ $row->id }}" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </button>
                    </div>
                @else
                    <div id="slide-spacing-{{ $row->id }}" class="carousel slide h-100" data-interval="false">
                        <div class="carousel-inner h-100">
                            @php
                                $row->gallery = $row->image_id . ',' . $row->gallery;
                                $gallery_img = [];
                            @endphp
                            @foreach (explode(',', $row->gallery) as $key => $img)
                                <div class="carousel-item  h-100 @if (!$key) active @endif">
                                    <a @if (!empty($blank)) target="_blank" @endif
                                        href="{{ $row->getDetailUrl($include_param ?? true) }}">
                                        {{-- Get img show --}}
                                        {!! get_image_tag_s3($img, 'medium', ['class' => 'img-responsive d-block', 'alt' => $row->title]) !!}
                                    </a>
                                    {{ $row->title }}
                                </div>
                            @endforeach

                        </div>
                        <button class="carousel-control-prev carousel-control" type="button"
                            data-target="#slide-spacing-{{ $row->id }}" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </button>
                        <button class="carousel-control-next carousel-control" type="button"
                            data-target="#slide-spacing-{{ $row->id }}" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </button>
                    </div>
                @endif
            @endif
        @endif
        @if (!$current_path)
            <div class="price-wrapper">
                <div class="price">
                    @if ($row->display_sale_price)
                        <span class="onsale">{{ $row->display_sale_price }}</span>
                        <span class="text-price">
                            {{ $row->display_price }}
                        </span>
                    @else
                        <span class="onsale">{{ $row->display_sale_price }}</span>
                        <span class="text-price">
                            {{-- {{ $row->display_price }} --}}
                            {{ formatNumberToVietnamese($row->price) }}
                        </span>
                    @endif
                    {{-- Turn off set day --}}
                    {{-- @if ($row->getBookingType() == 'by_day') 
                        <span class="unit">{{__("/day")}}</span>
                    @else
                        <span class="unit">{{__("/night")}}</span>
                    @endif --}}
                </div>
            </div>
        @endif
        <div class="service-wishlist {{ $row->isWishList() }}" data-id="{{ $row->id }}"
            data-type="{{ $row->type }}">
            <i class="fa fa-heart"></i>
        </div>
    </div>
    <div class="item-title">
        <a @if (!empty($blank)) target="_blank" @endif
            href="{{ $row->getDetailUrl($include_param ?? true) }}">
            @if ($row->is_instant)
                <i class="fa fa-bolt d-none"></i>
            @endif
            {{-- Change title --}}
            @if ($current_path)
                {!! clean($row->contact_name) !!}
            @else
                {!! clean($translation->title) !!}
            @endif
        </a>
        @if ($row->discount_percent)
            <div class="sale_info">{{ $row->discount_percent }}</div>
        @endif
    </div>
    <div class="location">
        @if ($current_path)
            @if (!empty($row->location->name))
                @php
                    $contact = isset($row->contact) ? $row->contact : null;
                @endphp
                @if ($contact)
                    <h5>{{ $contact }}</h5>
                @else
                    <h5>No Phone number</h5>
                @endif
                @php $location =  $row->location->translateOrOrigin(app()->getLocale()) @endphp
                <h6>{{ $location->name ?? '' }}</h6>
            @endif
        @else
            @if (!empty($row->location->name))
                @php $location =  $row->location->translateOrOrigin(app()->getLocale()) @endphp
                <h6>{{ $location->name ?? '' }}</h6>
            @endif
        @endif
    </div>
    @if (setting_item('space_enable_review'))
        <?php
        $reviewData = $row->getScoreReview();
        $score_total = $reviewData['score_total'];
        ?>
        <div class="service-review">
            <span class="rate">
                @if ($reviewData['total_review'] > 0)
                    {{ $score_total }}/5
                @endif <span class="rate-text">{{ $reviewData['review_text'] }}</span>
            </span>
            <span class="review">
                @if ($reviewData['total_review'] > 1)
                    {{ __(':number Reviews', ['number' => $reviewData['total_review']]) }}
                @else
                    {{ __(':number Review', ['number' => $reviewData['total_review']]) }}
                @endif
            </span>
        </div>
    @endif
    @if (!$current_path)
        <div class="amenities">
            {{-- Set all attribute --}}
            @if ($row->max_guests)
                <span class="amenity total" data-toggle="tooltip" title="Số tầng">
                    <i class="input-icon field-icon icofont-building-alt"></i> {{ $row->max_guests }}
                </span>
            @endif
            @if ($row->bed)
                <span class="amenity bed" data-toggle="tooltip" title="Phòng ngủ">
                    <i class="input-icon field-icon icofont-hotel"></i> {{ $row->bed }}
                </span>
            @endif
            @if ($row->bathroom)
                <span class="amenity bath" data-toggle="tooltip" title="Toilet">
                    <i class="input-icon field-icon icofont-bathtub"></i> {{ $row->bathroom }}
                </span>
            @endif
            @if ($row->square)
                <span class="amenity size" data-toggle="tooltip" title="Diện tích đất">
                    <i class="input-icon field-icon icofont-ruler-compass-alt"></i> {!! size_unit_format($row->square) !!}
                </span>
            @endif
        </div>
    @endif
</div>
