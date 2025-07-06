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
    .then(response => response.text())
    .then(data => {
        const container = document.querySelector(".cardh__container");
        if (container) {
            container.innerHTML = data;
        } else {
            console.error("No se encontró el contenedor .cardh__container para mostrar los resultados.");
        }
    })
    .catch(error => console.error('Error al cargar empresas:', error));
}
