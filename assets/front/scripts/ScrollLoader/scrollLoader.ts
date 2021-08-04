let page = 1;
let content = document.querySelector('.game-list');
let form: HTMLFormElement = document.querySelector('.toggleable');
let isFetching = false;
let hasMoreData = true;

export function handleForm() {
    // form.addEventListener('submit', (e) => {
    //     e.preventDefault();
        let data = new FormData(form);
        let body = {};
        for (let pair of data.entries()) {
            let key = pair[0].split('[').pop().split(']')[0]
            if (pair[1] && key !== '_token') {
                body[key] = pair[1];
            }
        }
        page = 1;
        content.innerHTML = '';
        fetchData(body);
    // })
}

function fetchData(body = null) {
    isFetching = true;
    console.log(body);
    fetch('/games-ajax?page=' + page, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'multipart/form-data'
        },
        body: JSON.stringify(body),
    })
        .then(result => result.json())
        .then(data => {
            content.insertAdjacentHTML('beforeend', data['html'])
            page++;
            isFetching = false;
            if (data.html === '') {
                hasMoreData = false;
            }
        });
}

if (location.pathname === '/game-list-ajax') {
    fetchData();
    window.addEventListener('scroll', () => {
        let d = document.documentElement;
        let offset = d.scrollTop + window.innerHeight;
        let height = d.offsetHeight;
        if (offset >= height && !isFetching && hasMoreData) {
            fetchData();
        }
    })


    // form.addEventListener('submit', (e) => {
    //     e.preventDefault();
    //     let data = new FormData(form);
    //     let body = {};
    //     for (let pair of data.entries()) {
    //         let key = pair[0].split('[').pop().split(']')[0]
    //         if (pair[1] && key !== '_token') {
    //             body[key] = pair[1];
    //         }
    //     }
    //     page = 1;
    //     content.innerHTML = '';
    //     fetchData(body);
    // })


}


