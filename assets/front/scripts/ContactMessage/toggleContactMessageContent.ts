export default function toggleContactMessageContent() {
    if(location.pathname === '/admin/message') {
        let liElements = document.querySelectorAll('.items-crud li');
        let contentElements = document.querySelectorAll('.items-crud li > div')
        let pElements: NodeListOf<HTMLParagraphElement> = document.querySelectorAll('.items-crud li p')
        liElements.forEach(li => {
            li.addEventListener('mouseenter', () => {
                let currentContent = li.querySelector('div');
                liElements.forEach(li => li.classList.remove('opened'));
                li.classList.add('opened')
                pElements.forEach(p => {
                    p.style.maxHeight = '0px';
                    p.style.opacity ='0';
                });
                let currentPElement = li.querySelector('p');
                currentPElement.style.maxHeight = currentPElement.scrollHeight + 'px';
                currentPElement.style.opacity = '1'
            })
        })
    }
}