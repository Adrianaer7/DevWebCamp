import Swal from "sweetalert2";

(function() {
    let eventos = [];   //conferencias agregadas al finalizar registro
    
    const resumen = document.querySelector("#registro-resumen")
    
    const eventosBoton = document.querySelectorAll(".evento__agregar")
    eventosBoton.forEach(boton => boton.addEventListener("click", seleccionarEvento))

    function seleccionarEvento(e) {
        if(eventos.length < 5) {
            e.target.disabled = true    //deshabilito el boton al que le doy click
            eventos = [...eventos, {
                id: e.target.dataset.id, //lo obtengo del data-id
                titulo: e.target.parentElement.querySelector(".evento__nombre").textContent.trim()  //escalo hacia el emento padre del boton
            }]

            mostrarEventos()        
        } else {
            Swal.fire({
                title: "Error",
                text: "Maximo 5 eventos",
                icon: "error",
                confirmButtonText: "OK"
            })
        }
        
        
    }

    function mostrarEventos() {
        //Evitar duplicados
        limpiarEventos()

        //Agregar nombre de la conferencia que selecciono el usuario
        if(eventos.length > 0) {
            eventos.forEach(evento => {
                const eventoDOM = document.createElement("DIV")
                eventoDOM.classList.add("registro__evento")

                const titulo = document.createElement("H3")
                titulo.classList.add("registro__nombre")
                titulo.textContent = evento.titulo

                const botonEliminar = document.createElement("button")
                botonEliminar.classList.add("registro__eliminar")
                botonEliminar.innerHTML = '<i class="fa-solid fa-trash"></i>'   //agregar icono font awesome
                botonEliminar.onclick = function() {
                    eliminarEvento(evento.id)
                }

                //Renderizar en el html
                eventoDOM.appendChild(titulo)
                eventoDOM.appendChild(botonEliminar)
                resumen.appendChild(eventoDOM)
            })
        }
    }

    //eliminar evento seleccionado del listado y hacer disponible el click en el listado a agregar
    function eliminarEvento(id) {
        eventos = eventos.filter(evento => evento.id !== id)    //elimino el evento del array seleccionado
        const botonAgregar = document.querySelector(`[data-id="${id}"]`)    //selecciono el boton al que le di click en agregar
        botonAgregar.disabled = false   //lo habilito
        mostrarEventos()
    }

    function limpiarEventos() {
        while(resumen.firstChild) { //elimino el elemento agregado anteriormente
            resumen.removeChild(resumen.firstChild)
        }
    }

})();
