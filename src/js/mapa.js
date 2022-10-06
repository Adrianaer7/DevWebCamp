if(document.querySelector("#mapa")) {
    const lat = -33.014744  //latitud de gchu (obtenida de hacer click en cualquiera parte de la ciudad en google maps)
    const lng = -58.521168  //longitud
    const zoom = 16
    const map = L.map('mapa').setView([lat, lng], zoom);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map)
        .bindPopup(`
            <h2 class="mapa__heading">DevWebCamp</h2>
            <p class="mapa__texto">Un punto cualquiera de Gualeguaychú</p>
        `)
        .openPopup();
}