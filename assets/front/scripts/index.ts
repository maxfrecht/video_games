import toggleContactMessageContent from "./ContactMessage/toggleContactMessageContent";
import {toggleForm} from "./Forms/toggleForm";
import {initSwiper} from "./Sliders/slider-games";
import {initNoUiSlider} from "./Forms/noUiSlider";
// import {scrollLoader} from "./ScrollLoader/scrollLoader";
import {initAOS} from "./scrollEffects/scrollEffects";
import './ScrollLoader/scrollLoader';

initAOS()
toggleContactMessageContent();
// scrollLoader();
initNoUiSlider();
toggleForm();
initSwiper();
