(function() {
    const horas = document.querySelector("#horas")

    if(horas) {
        
        const categoria = document.querySelector('[name="categoria_id"]')   //select del tipo de evento
        const dias = document.querySelectorAll('[name="dia"]')  //inputs radio
        const inputHiddenDia = document.querySelector('[name="dia_id"]')
        const inputHiddenHora = document.querySelector('[name="hora_id"]')

        //cuando se detecte cambios en el select de categoria o en el input de dia, envio sus values al objeto
        categoria.addEventListener("change", terminoBusqueda)
        dias.forEach(dia => dia.addEventListener("change", terminoBusqueda))

        let busqueda = {
            categoria_id: +categoria.value ?? "",    //guardo el value del select de la categorias traidas de la bd si estoy en el formulario de editar. Si es el form de crear, inicia vacio. Le pongo el + adelante para convertir el valor de string a integer
            dia: +inputHiddenDia.value ?? ""    //guardo el value que traigo del dia_id guardado en el evento en la bd si estoy en el formulario de editar. Si es el form de crear, inicia vacio. Le pongo el + adelante para convertir el valor de string a integer
        }

        //Si el busqueda ya contiene los datos de la bd, busco el evento directamente. Sino, tengo que esperar que haya un eventlistener para que se llene el objeto busqueda
        if(!Object.values(busqueda).includes("")) {
            //Creo esta funcio IIFE que se llama automaticamente
            (async () => {
                //Busco el evento segun lo que tenga en el objeto busqueda
                await buscarEventos()

                //Guardo el id del input oculto, que se llena manualmente clikeando la hora o trayendo la hora de la bd. 
                const id = inputHiddenHora.value

                //Selecciono el li que contenga el mismo valor que el input oculto en su atributo
                const horaSeleccionada = document.querySelector(`[data-hora-id="${id}"]`)

                //Le quito la calse deshabilitada
                horaSeleccionada.classList.remove("horas__hora--deshabilitada")

                //Le añado la clase seleccionada para identificar que ésta es la hora que se seleccionó y guardó en la bd del evento a editar
                horaSeleccionada.classList.add("horas__hora--seleccionada")

                //si le hago click a otra hora, y luego a esta seleccionada, elimino las clases y valores que se le asignaron al li e imput de la otra hora y se los asigno a esta
                horaSeleccionada.onclick = seleccionarHora
            }) ();
        }


        function terminoBusqueda(e) {
            //Guardo el value del elemento html en el objeto
            busqueda[e.target.name] = e.target.value

            //Reiniciar los campos ocultos
            inputHiddenHora.value = ""
            inputHiddenDia.value = ""

            //Deshabilitar estilos de la hora seleccionada anteriormente si hay un nuevo click en dias o categoria
            const horaPrevia = document.querySelector(".horas__hora--seleccionada")
            if(horaPrevia) {
                horaPrevia.classList.remove("horas__hora--seleccionada")
            }
            
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
            //Selecciono todos los li de horas
            const listadoHoras = document.querySelectorAll("#horas li")

            //Le añado la clase a todos los li
            listadoHoras.forEach(li => li.classList.add("horas__hora--deshabilitada"))            

            //Recorro los eventos y guardo las id de sus horas
            const horasTomadas = eventos.map(evento => evento.hora_id)

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

            //Agregar id del dia al campo oculto
            inputHiddenDia.value = document.querySelector('[name="dia"]:checked').value //selecciono el elemento con name=dia pero que sea el que está clickeado
        }
    }
}) ();