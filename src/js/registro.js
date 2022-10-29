import Swal from "sweetalert2";

(function() {
    let eventos = [];   //conferencias agregadas al finalizar registro
    
    const resumen = document.querySelector("#registro-resumen") //div

    if(resumen) {
        const eventosBoton = document.querySelectorAll(".evento__agregar")
        eventosBoton.forEach(boton => boton.addEventListener("click", seleccionarEvento))
            
        const formularioRegistro = document.querySelector("#registro")
        formularioRegistro.addEventListener("submit", submitFormulario)

        mostrarEventos()    //si no hay seleccionado  eventos o regalos, muestro el <p>

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
    
                    const botonEliminar = document.createElement("BUTTON")
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
            } else {
                const noRegistro = document.createElement("P")
                noRegistro.textContent = "No hay eventos. Añade hasta 5 del listado"
                noRegistro.classList.add("registro__texto")
                resumen.appendChild(noRegistro)
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
    
        async function submitFormulario(e) {
            e.preventDefault()
            
            //Obtener el regalo
            const regaloId = document.querySelector("#regalo").value
            const eventosId = eventos.map(evento => evento.id)
            
            if(eventosId.length === 0 || regaloId === "") { //si no se selecciono ningun evento ni regalo
                Swal.fire({
                    title: "Error",
                    text: "Selecciona al menos 1 evento y 1 regalo",
                    icon: "error",
                    confirmButtonText: "OK"
                })
                return
            }

            //Objeto de formdata
            const datos = new FormData()
            datos.append("eventos", eventosId)
            datos.append("regalo_id", regaloId)


            const url = "/finalizar-registro/conferencias"
            const respuesta = await fetch(url, {
                method: "POST",
                body: datos
            })
            const resultado = await respuesta.json()
            
        }
    
        
    }
    

    
})();
