let page = 1;
let content = document.querySelector('.game-list');
let form: HTMLFormElement = document.querySelector('.toggleable');
let isFetching = false;
let hasMoreData = true;
let bodyContent = null;

export function handleForm() {
    let data = new FormData(form);
    let body = {};

    for (let pair of data.entries()) {
        let key = pair[0].split('[').pop().split(']')[0]
        if (pair[1] && key !== '_token') {
            body[key] = pair[1];
        }
    }
    page = 1;
    hasMoreData = true;
    content.innerHTML = '';
    bodyContent = body;
    fetchData();
}

function fetchData() {
    isFetching = true;
    console.log(bodyContent);
    fetch('/games-ajax?page=' + page, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'multipart/form-data'
        },
        body: JSON.stringify(bodyContent),
    })
        .then(result => result.json())
        .then(data => {
            console.log(data.html)
            if (data.html === '') {
                hasMoreData = false;
            }

            content.insertAdjacentHTML('beforeend', data['html'])
            page++;

            isFetching = false;
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
}


