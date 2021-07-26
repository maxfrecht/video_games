// if(location.pathname === '/register' || location.pathname.startsWith('/admin/') || location.pathname.startsWith('/admin/update-')) {
//     let formGroups: NodeListOf<HTMLDivElement> = document.querySelectorAll('form div:not(div:last-of-type)');
//     formGroups?.forEach(formGroup => {
//         let label = formGroup.querySelector('label');
//         let input = formGroup.querySelector('select') ?? formGroup.querySelector('input');
//         // let textarea = formGroup.querySelector('textarea') ?? null;
//         // textarea ? textarea.innerHTML = 'Votre contenu' : '';
//         formGroup.removeChild(label);
//         input ? formGroup.removeChild(input) : '';
//         input ? formGroup.appendChild(input) : '';
//         formGroup.appendChild(label);
//         input?.setAttribute('placeholder', 'css')
//     })
// }