
<div class="g-header">
    <div class="left">
        <h1>{!! clean($translation->title) !!}</h1>
        @if ($translation->address)
            <p class="address"><i class="fa fa-map-marker"></i>
                {{ $translation->address }}
            </p>
        @endif
    </div>
    <div class="right">
        @if ($row->getReviewEnable())
            @if ($review_score)
                <div class="review-score">
                    <div class="head">
                        <div class="left">
                            {{-- Set in vi.json file đổi ngôn ngữ --}}
                            <span class="head-rating">{{ $review_score['score_text'] }}</span>
                            <span
                                class="text-rating">{{ __('from :number reviews', ['number' => $review_score['total_review']]) }}
                            </span>
                        </div>
                        <div class="score">
                            {{ $review_score['score_total'] }}<span>/5</span>
                        </div>
                    </div>
                    <div class="foot">
                        {{ __(':number% of guests recommend', ['number' => $row->recommend_percent]) }}
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
<div class="g-space-feature">
    {{-- Set attribute in detail --}}
    {{-- <i class="icofont-building-alt"> Set floors on max-guest</i> --}}
    <div class="row">
        @if (!empty($row->max_guests))
            <div class="col-xs-6 col-lg-3 col-md-6 res-mobile">
                <div class="item">
                    <div class="icon">
                        <i class="icofont-building-alt"></i>
                    </div>
                    <div class="info">
                        <h4 class="name">Số tầng</h4>
                        <p class="value">
                            {{ $row->max_guests }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if (!empty($row->bed))
            <div class="col-xs-6 col-lg-3 col-md-6 res-mobile">
                <div class="item">
                    <div class="icon">
                        <i class="icofont-hotel"></i>
                    </div>
                    <div class="info">
                        <h4 class="name">Phòng ngủ</h4>
                        <p class="value">
                            {{ $row->bed }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if ($row->bathroom)
            <div class="col-xs-6 col-lg-3 col-md-6 res-mobile">
                <div class="item">
                    <div class="icon">
                        <i class="icofont-bathtub"></i>
                    </div>
                    <div class="info">
                        <h4 class="name">Toilet</h4>
                        <p class="value">
                            {{ $row->bathroom }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if ($row->square)
            <div class="col-xs-6 col-lg-3 col-md-6 res-mobile">
                <div class="item">
                    <div class="icon">
                        <i class="icofont-ruler-compass-alt"></i>
                    </div>
                    <div class="info">
                        <h4 class="name">Diện tích đất</h4>
                        <p class="value">
                            {!! size_unit_format($row->square) !!}
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if (!empty($row->location->name))
            @php $location =  $row->location->translateOrOrigin(app()->getLocale()) @endphp
            <div class="col-xs-6 col-lg-3 col-md-6 res-mobile">
                <div class="item">
                    <div class="icon">
                        <i class="icofont-island-alt"></i>
                    </div>
                    <div class="info">
                        <h4 class="name">{{ __('Location') }}</h4>
                        <p class="value">
                            {{ $location->name ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
{{-- Get thumb image on S3 --}}
@if ($row->getGalleryS3())
    <div class="g-gallery">
        <div class="fotorama" data-width="100%" data-thumbwidth="135" data-thumbheight="135" data-thumbmargin="15"
            data-nav="thumbs" data-allowfullscreen="true">
            @foreach ($row->getGalleryS3() as $key => $item)
                <a href="{{ $item['large'] }}" data-thumb="{{ $item['thumb'] }}" data-alt="{{ __('Gallery') }}"></a>
            @endforeach
        </div>
        <div class="social">
            <div class="social-share">
                <span class="social-icon">
                    <i class="icofont-share"></i>
                </span>
                <ul class="share-wrapper">
                    <li>
                        <a class="facebook"
                            href="https://www.facebook.com/sharer/sharer.php?u={{ $row->getDetailUrl() }}&amp;title={{ $translation->title }}"
                            target="_blank" rel="noopener" original-title="{{ __('Facebook') }}">
                            <i class="fa fa-facebook fa-lg"></i>
                        </a>
                    </li>
                    <li>
                        <a class="twitter"
                            href="https://twitter.com/share?url={{ $row->getDetailUrl() }}&amp;title={{ $translation->title }}"
                            target="_blank" rel="noopener" original-title="{{ __('Twitter') }}">
                            <i class="fa fa-twitter fa-lg"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="service-wishlist {{ $row->isWishList() }}" data-id="{{ $row->id }}"
                data-type="{{ $row->type }}">
                <i class="fa fa-heart-o"></i>
            </div>
        </div>
    </div>
@endif
@if ($translation->content)
    <div class="g-overview">
        <h3>{{ __('Description') }}</h3>
        <div class="description">
            <?php echo $translation->content; ?>
        </div>
    </div>
@endif

@include('Space::frontend.layouts.details.space-attributes')
@if ($translation->faqs)
    <div class="g-faq">
        <h3> {{ __('FAQs') }} </h3>
        @foreach ($translation->faqs as $item)
            <div class="item">
                <div class="header">
                    <i class="field-icon icofont-support-faq"></i>
                    <h5>{{ $item['title'] }}</h5>
                    <span class="arrow"><i class="fa fa-angle-down"></i></span>
                </div>
                <div class="body">
                    {{ $item['content'] }}
                </div>
            </div>
        @endforeach
    </div>
@endif
@includeIf('Hotel::frontend.layouts.details.hotel-surrounding')
@if ($row->map_lat && $row->map_lng)
    <div class="g-location">
        <h3>{{ __('Location') }}</h3>
        <div class="location-map">
            <div id="map_content"></div>
        </div>
    </div>
@endif
