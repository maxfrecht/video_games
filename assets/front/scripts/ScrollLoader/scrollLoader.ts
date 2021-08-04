if(location.pathname === '/game-list-ajax') {

    let page = 1;
    let content = document.querySelector('.game-list');
    fetchData();
    window.addEventListener('scroll', () => {
        let d = document.documentElement;
        let offset = d.scrollTop + window.innerHeight;
        let height = d.offsetHeight;
        if (offset >= height) {
            fetchData();
        }
    })

    function fetchData() {
        fetch('/games-ajax?page=' + page)
            .then(result => result.json())
            .then(data => {
                // content.innerHTML += data['html'];
                content.insertAdjacentHTML('beforeend', data['html'])
            });
        page++;
    }
}


