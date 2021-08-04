import * as noUiSlider from 'nouislider';
import {handleForm} from "../ScrollLoader/scrollLoader";
export function initNoUiSlider() {
    if (location.pathname.startsWith('/game-list')) {
        let priceMinInput: HTMLInputElement = document.querySelector('#filters_game_pricemin');
        let priceMaxInput: HTMLInputElement = document.querySelector('#filters_game_pricemax');
        let noteMinInput: HTMLInputElement = document.querySelector('#filters_game_noteGlobalMin');
        let noteMaxInput: HTMLInputElement = document.querySelector('#filters_game_noteGlobalMax');

        let form = document.querySelector('form');

        let priceSlider = document.getElementById('priceSlider');
        let noteSlider = document.getElementById('noteSlider');

        let priceslider = noUiSlider.create(priceSlider, {
            start: [10, 90],
            connect: true,
            step: 1,
            range: {
                min: 1,
                max: 100
            }
        })
        let noteslider = noUiSlider.create(noteSlider, {
            start: [1, 10],
            connect: true,
            step: 1,
            range: {
                min: 1,
                max: 10
            }
        })

        let noteSliderValueElement = document.querySelector('#noteSliderValue');
        noteslider.on('update', () => {
            noteSliderValueElement.innerHTML = `Note min : <strong>${parseInt(noteslider.get()[0])}</strong> - Note max : <strong>${parseInt(noteslider.get()[1])}</strong>`;
        })

        let priceSliderValueElement = document.querySelector('#priceSliderValue');
        priceslider.on('update', () => {
            priceSliderValueElement.innerHTML = `Prix min : <strong>${parseInt(priceslider.get()[0]).toFixed()}€</strong> - Prix max : <strong>${parseInt(priceslider.get()[1]).toFixed()}€</strong>`;
        })

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            priceMinInput.value = priceslider.get()[0];
            priceMaxInput.value = priceslider.get()[1];
            noteMinInput.value = noteslider.get()[0];
            noteMaxInput.value = noteslider.get()[1];
            handleForm();
        })
    }
}
