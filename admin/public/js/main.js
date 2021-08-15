let setting = document.querySelector('.setting-menu .setting-icon a');

setting.addEventListener('click' , function(){
    //console.log('clicked');
    //document.querySelector('.setting-menu').style.right = '0%';
    document.querySelector('.setting-menu').classList.toggle('setting-open');
})