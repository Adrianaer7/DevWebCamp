(function () {
    const ponentesInput = document.querySelector("#ponentes")
   
    if(ponentesInput) {
        let ponentes = []
        let ponentesFiltrados = []
        
        //selecciono el ul
        const listadoPonentes = document.querySelector("#listado-ponentes") //ul
        const ponenteHidden = document.querySelector('[name="ponente_id"]')
        obtenerPonentes()
        
        //Cuando se escriba en el input
        ponentesInput.addEventListener("input", buscarPonentes)
        
        //si el input hidden trae de la bd del evento el id del ponente
        if(ponenteHidden.value) {
            (async () => {
                //Obtener objeto con todos los datos del ponente
                const ponente = await obtenerPonente(ponenteHidden.value)
                const {nombre, apellido} = ponente

                //Insertar en el html
                const ponenteDOM = document.createElement("LI")
                ponenteDOM.classList.add("listado-ponentes__ponente", "listado-ponentes__ponente--seleccionado")
                ponenteDOM.textContent = `${nombre} ${apellido}`
                listadoPonentes.appendChild(ponenteDOM) //agrego el li al ul
            }) ();
        }
        
        async function obtenerPonentes() {
            const url = `/api/ponentes`
            const respuesta = await fetch(url) 
            const resultado = await respuesta.json()
            
            formatearPonentes(resultado)
        }

        async function obtenerPonente(id) {
            const url = `/api/ponente?id=${id}`
            const respuesta = await fetch(url) 
            const resultado = await respuesta.json()
            return resultado
        }

        function formatearPonentes(arrayPonentes = []) {    //inicio el array vacio si no existe resultado
            ponentes = arrayPonentes.map(ponente => {
                return {    //en el array de ponentes solo coloco los datos que necesito
                    id: ponente.id,
                    nombre: `${ponente.nombre.trim()} ${ponente.apellido.trim()}`
                }
            })
        }

        function buscarPonentes(e) {
            //guardo lo que escribo
            const busqueda = e.target.value

            //reseteo el input hidden
            ponenteHidden.value = ""
            
            //si ya ingresé mas de 3 letras en el input
            if(busqueda.length > 3) {   
                const expresion = new RegExp(busqueda, "i") //creo una nueva expresion regular, con "i" le digo que no importa si escribo con mayus o min
                ponentesFiltrados = ponentes.filter(ponente => {
                    if(ponente.nombre.toLowerCase().search(expresion) !== -1) { //.search() devuelve -1 si no se encuentran coincidencias, sino devuelve 0. Se usa .search() porque se usa RegExp
                        return ponente; //guardo el objeto entero en el el array de filtrados
                    }
                })
            } else {
                ponentesFiltrados = []
            }
            mostrarPonentes()
        }
        
        function mostrarPonentes() {
            //si hay elementos li dentro del ul los elimino, para evitar duplicados por cada letra ingresada
            while(listadoPonentes.firstChild) {
                listadoPonentes.removeChild(listadoPonentes.firstChild)
            }

            if(ponentesFiltrados.length > 0) {
                ponentesFiltrados.forEach(ponente => {
                    //Creo la lista
                    const ponenteHTML = document.createElement("LI")
    
                    //Añado la clase
                    ponenteHTML.classList.add("listado-ponentes__ponente")
                    
                    //Le coloco el nombre para que sea visible
                    ponenteHTML.textContent = ponente.nombre
    
                    //Le añado el atributo personalizado data-ponente-id
                    ponenteHTML.dataset.ponenteId = ponente.id

                    //cuando haga click
                    ponenteHTML.onclick = seleccionarPonente

                    //Añadir al DOM
                    listadoPonentes.appendChild(ponenteHTML)
                })
            } else {
                const noResultados = document.createElement("P")
                noResultados.classList.add("listado-ponentes__no-resultado")
                noResultados.textContent = "No hay resultados"
                listadoPonentes.append(noResultados)
            }

        }

        function seleccionarPonente(e) {
            //Obtengo el li al que le hice click
            const ponente = e.target

            //remover clase al li en caso de que lo haya seleccionado previamente
            const ponentePrevio = document.querySelector(".listado-ponentes__ponente--seleccionado")
            if(ponentePrevio) {
                ponentePrevio.classList.remove("listado-ponentes__ponente--seleccionado")
            }
            
            //agrego clase
            ponente.classList.add("listado-ponentes__ponente--seleccionado")

            //Agrego el valor del atributo personalizado del li que seleccioné
            ponenteHidden.value = ponente.dataset.ponenteId
        }
    }
}) ();