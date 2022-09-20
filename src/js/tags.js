(function () {  //esta funcion encerrada en parentesis se llama iife. Esta funcion se ejecuta solo si la pagina donde estoy hay un input con ese id, en las demas paginas no se ejecuta
    const tagsInput = document.querySelector("#tags_input")
    if(tagsInput) {
        const tagsDiv = document.querySelector("#tags")
        const tagsInputHidden = document.querySelector("[name='tags']")
        let tags = []

        //Escuchar los cambios en el input. keypress es escuchar al ingreso de letras
        tagsInput.addEventListener("keypress", guardarTag)

        function guardarTag(e) {
            if(e.keyCode === 44) {  //detecta el ingreso de una ,
                if(e.target.value.trim() === "" || e.target.value <1) return    //si se ingresaron solo espacios, no agrega nada al array
                e.preventDefault()  //cuando presione una coma, no la agrega al formulario. Con esto elimino la , luego de ingresarla en el input
                tags = [...tags, e.target.value.trim()] //guardo en el array cada palabra
                tagsInput.value = ""    //vacío el input luego de ingresar la palabra
                mostrarTags()
            }
        }

        function mostrarTags() {
            tagsDiv.textContent = "";
            tags.forEach(tag => {
                const etiqueta = document.createElement("LI")   //creo un <li> por cada elemento en el array
                etiqueta.classList.add("formulario__tag")
                etiqueta.textContent = tag  //le agrego el elemento al li
                etiqueta.ondblclick = elimininarTag //cuando haga doble click
                tagsDiv.appendChild(etiqueta)   //le agrego todas las li al div
            })
            
            actualizarInputHidden()
        }

        function elimininarTag(e) {
            e.target.remove()   //elimino el li
            tags = tags.filter(tag => tag !== e.target.textContent) //elimino el tag del arreglo filtrando los que sean distintos del contenido del li
            actualizarInputHidden()
        }

        function actualizarInputHidden() {
            tagsInputHidden.value = tags.toString() //coloco todos lo que haya en el array de tags en el value del input oculto
        }
    }
}) ()