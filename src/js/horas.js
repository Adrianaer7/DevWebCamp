(function() {
    const horas = document.querySelector("#horas")

    if(horas) {
        let busqueda = {
            categoria_id: "",
            dia: ""
        }

        const categoria = document.querySelector('[name="categoria_id"]')   //select del tipo de evento
        const dias = document.querySelectorAll('[name="dia"]')  //inputs radio
        const inputHiddenDia = document.querySelector('[name="dia_id"]')
        const inputHiddenHora = document.querySelector('[name="hora_id"]')

        //cuando se detecte cambios en el select de categoria o en el input de dia, envio sus values al objeto
        categoria.addEventListener("change", terminoBusqueda)
        dias.forEach(dia => dia.addEventListener("change", terminoBusqueda))

        function terminoBusqueda(e) {
            busqueda[e.target.name] = e.target.value
            
            if(Object.values(busqueda).includes("")) {
                return
            }
            buscarEventos()
        }

        //si el objeto de busqueda tiene las 2 keys con valor
        async function buscarEventos() {
            const {categoria_id, dia} = busqueda

            const url = `/api/eventos-horario?dia_id=${dia}&categoria_id=${categoria_id}`
            const resultado = await fetch(url)  //le hago un get a la url
            const eventos = await resultado.json()  //consumo los datos provenientes del echo del APIEventos.php

            obtenerHorasDisponibles(eventos)
        }

        function obtenerHorasDisponibles(eventos) {
            //Recorro los eventos y guardo las id de sus horas
            const horasTomadas = eventos.map(evento => evento.hora_id)

            //Selecciono todos los li de horas
            const listadoHoras = document.querySelectorAll("#horas li")

            //Convierto el listadoHoras que es un NodeList a un array
            const listadoHorasArray = Array.from(listadoHoras)  

            //Filtro todas las horas que no existan en horasTomadas
            const resultado = listadoHorasArray.filter(li => !horasTomadas.includes(li.dataset.horaId)) //dataset hace referencia a el atributo personalizado llamado "data-hora-id" en el li del formulario
            
            //A cada hora disponible le saco la clase --deshabilitada por default para que se vean como disponibles gracias a css
            resultado.forEach(li => li.classList.remove("horas__hora--deshabilitada"))

            //selecciono todos los li de horas que no tengan la clase --deshabilitada para que solo esos li puedan reaccionar a un event
            const horasDisponibles = document.querySelectorAll("#horas li:not(.horas__hora--deshabilitada)") 
            
            horasDisponibles.forEach(hora => hora.addEventListener("click", seleccionarHora))
        }

        function seleccionarHora(e) {
            //Deshabilitar la hora seleccionada anteriormente si hay un nuevo click
            const horaPrevia = document.querySelector(".horas__hora--seleccionada")
            if(horaPrevia) {
                horaPrevia.classList.remove("horas__hora--seleccionada")
            }

            //Agregar clase a la hora seleccionada
            e.target.classList.add("horas__hora--seleccionada") //agrego la clase al li que clickee

            //Agregar id de la hora al campo oculto
            inputHiddenHora.value = e.target.dataset.horaId //dataset hace referencia a el atributo personalizado llamado "data-hora-id" en el li del formulario
        }
    }
}) ();