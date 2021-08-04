import toggleContactMessageContent from "./ContactMessage/toggleContactMessageContent";
import {toggleForm} from "./Forms/toggleForm";
import {initSwiper} from "./Sliders/slider-games";
import {initNoUiSlider} from "./Forms/noUiSlider";
// import {scrollLoader} from "./ScrollLoader/scrollLoader";
import {initAOS} from "./scrollEffects/scrollEffects";

initAOS()
toggleContactMessageContent();
initNoUiSlider();
import './ScrollLoader/scrollLoader';
toggleForm();
initSwiper();
