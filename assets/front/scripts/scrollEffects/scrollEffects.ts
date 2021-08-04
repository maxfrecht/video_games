import * as Aos from 'aos';
// import {Aos} from 'aos';

export function initAOS() {
    Aos.init({
        once: true,
        easing: "ease-in-quart",
        offset: 180
    });
}
