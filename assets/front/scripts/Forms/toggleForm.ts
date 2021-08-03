export function toggleForm() {
    if(location.pathname === '/game-list') {
        let btnToggleElement = document.querySelector('.toggle-form');
        let form = document.querySelector('form');
        btnToggleElement.addEventListener('click', (e) => {
            if(!form.style.maxHeight || form.style.maxHeight === '0px') {
                btnToggleElement.classList.add('open');
                form.style.maxHeight = form.scrollHeight + 'px';
            } else {
                btnToggleElement.classList.remove('open');
                form.style.maxHeight = '0px';
            }
        })
    }
}