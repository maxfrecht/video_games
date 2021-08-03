export function scrollLoader() {
    window.addEventListener('scroll', () => {
        let d = document.documentElement;
        let offset = d.scrollTop + window.innerHeight;
        let height = d.offsetHeight;
        if(offset>= height) {
            console.log('At the bottom');
        }
    })
}