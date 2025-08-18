document.getElementById("estadoDropdownBtn").addEventListener("click", function () {
    this.parentElement.classList.toggle("show");
});

document.querySelectorAll("#estadoDropdownMenu a").forEach(item => {
    item.addEventListener("click", function (e) {
        e.preventDefault();
        const estado = this.dataset.estado;
        getEmpresasPorEstado(estado);
        document.querySelector(".dropdown").classList.remove("show");
    });
});

function getEmpresasPorEstado(estado) {
    const formData = new URLSearchParams();
    formData.append('accion', 'filtrarPorEstado');
    formData.append('estado', estado);

    fetch('../../../controllers/UsuarioController.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData
    })
    .then(response => response.json())
    .then(empresas => {
        renderEmpresas(empresas);
    })
    .catch(error => console.error('Error al cargar empresas:', error));
}

// Copiamos la función renderEmpresas del viewempresas.js para asegurar el renderizado de cards
function renderEmpresas(empresas) {
    const container = document.querySelector(".cardh__container");
    if (!container) return;
    container.innerHTML = "";
    if (!Array.isArray(empresas)) {
        container.innerHTML = "<p>Error al cargar las empresas. Intenta recargar la página.</p>";
        return;
    }
    if (empresas.length === 0) {
        container.innerHTML = "<p>No se encontraron empresas.</p>";
        return;
    }
    empresas.forEach(empresa => {
        if (!empresa || !empresa.razon_social || !empresa.nit) return;
        const card = document.createElement("article");
        card.classList.add("cardh");
        card.innerHTML = `
            <div class="card-text">
                <h2 class="card-title">${empresa.razon_social}</h2>
                <p class="card-subtitle">${empresa.nit}</p>
            </div>
        `;
        card.style.cursor = 'pointer';
        card.addEventListener('click', () => {
            window.location.href = `Empresa.html?id=${empresa.id}`;
        });
        container.appendChild(card);
    });
}
