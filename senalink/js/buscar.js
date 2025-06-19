document.addEventListener('DOMContentLoaded', () => {
    const inputBusqueda = document.querySelector('.search-container input');
    const contenedorResultados = document.querySelector('#resultados');
    const contenedorTarjetas = document.querySelector('.cardh__container');

    inputBusqueda.addEventListener('input', () => {
        const query = inputBusqueda.value.trim();

        if (query.length === 0) {
            contenedorResultados.innerHTML = '';
            contenedorTarjetas.style.display = 'flex';
            return;
        }

        fetch(`../../../php/buscar_empresas.php?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                contenedorTarjetas.style.display = 'none'; // Oculta las tarjetas por defecto
                contenedorResultados.innerHTML = '';

                if (data.length === 0) {
                    contenedorResultados.innerHTML = '<p>No se encontraron resultados.</p>';
                    return;
                }

                data.forEach(empresa => {
                    const card = document.createElement('article');
                    card.className = 'cardh';
                    card.innerHTML = `
                        <div class="card-text">
                            <h2 class="card-title">${empresa.razon_socia}</h2>
                            <p class="card-subtitle">${empresa.nit}}</p>
                        </div>
                    `;
                    contenedorResultados.appendChild(card);
                });
            })
            .catch(error => console.error('Error:', error));
    });
});
