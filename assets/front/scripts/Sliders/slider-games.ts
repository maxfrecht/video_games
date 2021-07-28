import Swiper from "swiper";

export function initSwiper() {
    const swiper = new Swiper('.swiper-games', {
        slidesPerView: 2,
        pagination: false,
        spaceBetween:40,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    })
}