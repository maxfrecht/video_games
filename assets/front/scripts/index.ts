if(location.pathname === '/register' || location.pathname.startsWith('/admin/add-') || location.pathname.startsWith('/admin/update-')) {
    let formGroups: NodeListOf<HTMLDivElement> = document.querySelectorAll('form div:not(div:last-of-type)');
    formGroups.forEach(formGroup => {
        let label = formGroup.querySelector('label');
        let input = formGroup.querySelector('input');
        formGroup.removeChild(label);
        formGroup.removeChild(input);
        formGroup.appendChild(input);
        formGroup.appendChild(label);
        input.setAttribute('placeholder', 'css')
    })
}