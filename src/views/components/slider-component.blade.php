<div class="blog-slider-area">
    <div class="swiper-container">
        <div class="swiper blog-slider">
            <div class="swiper-wrapper">
            @foreach($contents as $content)
                {!! $content !!}
            @endforeach
            </div>
            <div class="swiper-arrows">
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </div>
</div>