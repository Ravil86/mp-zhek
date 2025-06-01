//Fancybox 4
// const myCarousel = new Carousel(document.querySelector(".carousel"), {
//     slidesPerPage: 'auto',
// });

const container = document.getElementById("galleryCarousel");
const options = {
    infinite: false,
    Navigation: {
        nextTpl: '',
        prevTpl: '',
        //     nextTpl: '<svg style="transform: scale(-1,1)" width="49" height="75" viewBox="0 0 49 75" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M37.8696 36.37L46.8096 45.31L37.8696 54.25L46.8096 63.18L37.8696 72.12L28.9296 63.18L19.9996 54.25L11.0596 45.31L11.0596 45.3L2.12957 36.37L11.0696 27.44L19.9996 18.5L28.9396 9.57L37.0096 1.49L38.7296 1.49L46.8096 9.57L37.8696 18.5L46.8096 27.44L37.8696 36.37ZM37.8696 36.37L28.9396 27.44L28.9496 27.45L20.0096 36.38L28.9396 45.31L37.8696 36.37Z" stroke="#0FA3C5" stroke-width="3" stroke-miterlimit="10"/></svg>',
        //     prevTpl: '<svg width="49" height="75" viewBox="0 0 49 75" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M37.8696 36.37L46.8096 45.31L37.8696 54.25L46.8096 63.18L37.8696 72.12L28.9296 63.18L19.9996 54.25L11.0596 45.31L11.0596 45.3L2.12957 36.37L11.0696 27.44L19.9996 18.5L28.9396 9.57L37.0096 1.49L38.7296 1.49L46.8096 9.57L37.8696 18.5L46.8096 27.44L37.8696 36.37ZM37.8696 36.37L28.9396 27.44L28.9496 27.45L20.0096 36.38L28.9396 45.31L37.8696 36.37Z" stroke="#0FA3C5" stroke-width="3" stroke-miterlimit="10"/></svg>',
    },

};
new Carousel(container, options);