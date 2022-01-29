const navSlide = () => {
    const burger = document.querySelector('.menu-burger');
    const nav = document.querySelector('.nav-icons');

    burger.addEventListener('click', () => {
        nav.classList.toggle('nav-active');
    });
}

navSlide();