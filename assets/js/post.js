window.onload = function(){
    var open = document.getElementById('post-btn');
    var modal_container = document.getElementById('outer_modal_container');
    var close = document.getElementById('close-btn');
    var close2 = document.getElementById('close-btn-2');
    var body = document.getElementById('body');
    var next = document.getElementById('post-next-btn');
    var previous = document.getElementById('post-next-btn-2');
    var page1 = document.getElementById('first-page');
    var page2 = document.getElementById('second-page');

    open.addEventListener('click', () => {
        modal_container.classList.add('show');
        body.style.overflow = "hidden";
    });

    close.addEventListener('click', () => {
        modal_container.classList.remove('show');
        body.style.overflow = "visible";
    });

    close2.addEventListener('click', () => {
        modal_container.classList.remove('show');
        body.style.overflow = "visible";
    });

    next.addEventListener('click', () => {
        $("#first-page").hide();
		$("#second-page").show();
    });

    previous.addEventListener('click', () => {
        page1.style.display = "block";
        page2.style.display = "none";
    });
}