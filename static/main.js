window.onload = () => {
    let modal_status1 = document.querySelector('.modal.msuccess');
    let modal_status2 = document.querySelector('.modal.merror');

    if(modal_status1 || modal_status2){
        setTimeout(() => {
            if(modal_status1){
                document.querySelector('main section').removeChild(modal_status1);
            }
            else{
                document.querySelector('main section').removeChild(modal_status2);
            }
        }, 2500);
    }

    let modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('click', (e) => {
            if(e.currentTarget == modal){
                modal.classList.remove('shown');
            }
        })
    })
}
function openModal(modal_i){
    modal_i.classList.add('shown');
}
function closeModal(){
    for(let i = 0; i < modals.length; i++){
        if(modals[i].length){
            for(let j = 0; j < modals[i].length; j++) modals[i][j].classList.remove('shown');
        }
        else{
            modals[i].classList.remove('shown');
        }
    }
}

// do interface later i guess
// let videos = document.querySelectorAll('video');
// if(videos){
//     videos.forEach(video => {
//         let interface = document.createElement('div');
//         interface.className = 'vinterface_short';
//         let play_button_svg = '<?xml version="1.0" encoding="iso-8859-1"?><svg fill="#000000" height="800px" width="800px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"viewBox="0 0 60 60" xml:space="preserve"><g><path d="M45.563,29.174l-22-15c-0.307-0.208-0.703-0.231-1.031-0.058C22.205,14.289,22,14.629,22,15v30 c0,0.371,0.205,0.711,0.533,0.884C22.679,45.962,22.84,46,23,46c0.197,0,0.394-0.059,0.563-0.174l22-15 C45.836,30.64,46,30.331,46,30S45.836,29.36,45.563,29.174z M24,43.107V16.893L43.225,30L24,43.107z"/> <path d="M30,0C13.458,0,0,13.458,0,30s13.458,30,30,30s30-13.458,30-30S46.542,0,30,0z M30,58C14.561,58,2,45.439,2,30 S14.561,2,30,2s28,12.561,28,28S45.439,58,30,58z"/></g></svg>';
//         interface.innerHTML = play_button_svg;
//         video.appendChild(interface);
//     });
// }