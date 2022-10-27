<main class="registro">
    <h2 class="registro__heading"><?php echo $titulo; ?></h2>
    <p class="registro__descripcion">Elige tu plan</p>

    <div class="paquetes__grid">
        <div class="paquete">
            <h3 class="paquete__nombre">Pase Gratis</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento">Acceso a DevWebCamp</li>
            </ul>
            <p class="paquete__precio">$0</p>
            <form method="POST" action="/finalizar-registro/gratis">
                <input type="submit" class="paquetes__submit" value="Inscripcion Gratis">
            </form>
        </div>

        <div class="paquete">
            <h3 class="paquete__nombre">Pase Presencial</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento">Acceso Presencial a DevWebCamp</li>
                <li class="paquete__elemento">Pase por 2 dias</li>
                <li class="paquete__elemento">Acceso a talleres y conferencias</li>
                <li class="paquete__elemento">Acceso a las grabaciones</li>
                <li class="paquete__elemento">Acceso a Camisa del Evento</li>
                <li class="paquete__elemento">Comida y Bebida</li>
            </ul>

            <p class="paquete__precio">$199</p>
            <!--boton pago paypal-->
            <div id="smart-button-container">
              <div style="text-align: center;">
                <div id="paypal-button-container"></div>
              </div>
            </div>

        </div>

        <div class="paquete">
            <h3 class="paquete__nombre">Pase Virtual</h3>
            <ul class="paquete__lista">
                <li class="paquete__elemento">Acceso virtual a DevWebCamp</li>
                <li class="paquete__elemento">Pase por 2 dias</li>
                <li class="paquete__elemento">Enlace a Talleres y conferencias</li>
                <li class="paquete__elemento">Acceso a Grabaciones</li>

            </ul>
            <p class="paquete__precio">$49</p>
        </div>
    </div>
</main>

  <!--script paypal-->
  <script src="https://www.paypal.com/sdk/js?client-id=ARV5zbhV00KgrFv8kfl9Ya0OyOpHnoHkT0GgvE7h7paCz7fZhz-0KPKYqS8CdL4nPWtF7beZsFwaZUdu&enable-funding=venmo&currency=USD" data-sdk-integration-source="button-factory"></script>
  <script>
    function initPayPalButton() {
      paypal.Buttons({
        style: {
          shape: 'rect',
          color: 'blue',
          layout: 'vertical',
          label: 'pay',
          
        },

        createOrder: function(data, actions) {  //cuando se pongo pagar con paypal se genera la ventana emergente con estos datos
          return actions.order.create({
            purchase_units: [{"description":"1","amount":{"currency_code":"USD","value":199}}]
          });
        },

        onApprove: function(data, actions) {  //si el pago es aprobado
          return actions.order.capture().then(function(orderData) {
            const datos = new FormData()
            datos.append("paquete_id", orderData.purchase_units[0].description) //dentro del objeto datos guardo la propiedad paquete_id y le asigno el valor de la descripcion que le puse arriba
            datos.append("pago_id", orderData.purchase_units[0].payments.captures[0].id)  // el id de pago que genera automaticamente paypal al pagar. Este valor se puede encontrar en ID de transaccion de la factura en la cuenta bussiness 

            const url = "/finalizar-registro/pagar"
            fetch(url, {
              method: "POST",
              body: datos //al hacer este fetch, puedo obtener los datos desde $_POST en PHP
            })
            .then(respuesta => respuesta.json())  //obtengo la respuesta de PHP
            .then(resultado => {
              if(resultado.resultado) { //si el registro se guardo correctamente en la bd desde php, resultado es true por eso redirijo
                actions.redirect("http://localhost:3000/finalizar-registro/conferencias")
              }
            })
          });
        },

        onError: function(err) {
          console.log(err);
        }
      }).render('#paypal-button-container');
    }
    initPayPalButton();
  </script>