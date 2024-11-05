let modal_status = document.querySelector('.modal.msuccess, .modal.merror');

if(modal_status){
    setTimeout(() => {
        document.querySelector('main section').removeChild(modal_status);
    }, 5000);
}