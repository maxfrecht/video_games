import Swiper, { Navigation } from 'swiper';

Swiper.use([Navigation]);

export function initSwiper() {
    const swiper = new Swiper('.swiper-games', {
        slidesPerView: 3,
        spaceBetween:40,
        grabCursor: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    })
}