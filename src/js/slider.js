import Swiper, {Navigation} from "swiper";
import "swiper/css"; //para poder usar el swiper
import "swiper/css/navigation";  //para poder usar los botones de prev y siguiente

document.addEventListener("DOMContentLoaded", function() {
    if(document.querySelector(".slider")) {
        const opciones = {
            slidesPerView: 1,   //cantidad de cards que se muestran en la pantalla
            spaceBetween: 15,   //separacion en px
            freeMode: true,  //por si no funcionan bien los slides
            navigation: {
                prevEl: ".swiper-button-prev",
                nextEl: ".swiper-button-next"

            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
                1200: {
                    slidesPerView: 4,
                },
            }
        }
        Swiper.use([Navigation])
        new Swiper(".slider", opciones)
    }
})