(function () {
    const ponentesInput = document.querySelector("#ponentes")

    if(ponentesInput) {
        let ponentes = []
        let ponentesFiltrados = []

        obtenerPonentes()

        //Cuando se escriba en el input
        ponentesInput.addEventListener("input", buscarPonentes)

        async function obtenerPonentes() {
            
            const url = `/api/ponentes`
            const respuesta = await fetch(url) 
            const resultado = await respuesta.json()
            
            formatearPonentes(resultado)
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
            const busqueda = e.target.value
            if(busqueda.length > 3) {   //si ya ingresé mas de 3 letras en el input
                const expresion = new RegExp(busqueda, "i") //creo una nueva expresion regular, con "i" le digo que no importa si escribo con mayus o min
                ponentesFiltrados = ponentes.filter(ponente => {
                    if(ponente.nombre.toLowerCase().search(expresion) !== -1) { //.search() devuelve -1 si no se encuentran coincidencias, sino devuelve 0. Se usa .search() porque se usa RegExp
                        return ponente; //guardo el objeto entero en el el array de filtrados
                    }
                })
            }
        }
    }


}) ();