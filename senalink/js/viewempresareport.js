document.addEventListener("DOMContentLoaded", function () {
    // Cargar la lista de empresas
    fetch("../../../controllers/UsuarioController.php?action=listarEmpresas")
        .then(response => response.json())
        .then(empresas => {
            const container = document.querySelector(".cardh__container");

            if (!container) {
                console.error("No se encontró el contenedor con clase .cardh__container");
                return;
            }

            container.innerHTML = ""; // Limpiar contenido inicial

            empresas.forEach(empresa => {
                const card = document.createElement("article");
                card.classList.add("cardh");

                card.innerHTML = `
                    <a href="EmpresaReport.html?id=${empresa.id}">
                        <div class="card-text">
                            <h2 class="card-title">${empresa.razon_social}</h2>
                            <p class="card-subtitle">${empresa.nit}</p>
                        </div>
                    </a>
                `;

                container.appendChild(card);
            });
        })
        .catch(error => {
            console.error("Error al cargar las empresas:", error);
        });
});

// Cargar detalles de la empresa específica
document.addEventListener("DOMContentLoaded", function () {
    // Obtener el ID desde la URL
    const empresaId = new URLSearchParams(window.location.search).get('id');

    if (empresaId) {
        // Hacer una solicitud GET al backend para obtener los detalles de la empresa
        fetch(`http://localhost/senalink5/senalink5/senalink/controllers/UsuarioController.php?action=detalleEmpresa&id=${empresaId}`)
            .then(response => response.json())
            .then(data => {
                // Verificar si el servidor devolvió un error
                if (data.error) {
                    console.error(data.error); // Mostrar el error en la consola
                } else {
                    // Si no hay error, actualizar los elementos HTML con los datos de la empresa
                    document.getElementById('empresa-id').textContent = empresaId; // Muestra el ID
                    document.getElementById('nit').textContent = data.nit; // Muestra el NIT
                    document.getElementById('representante').textContent = data.representante_legal; // Representante Legal
                    document.getElementById('razon-social').textContent = data.razon_social; // Razón Social
                    document.getElementById('empresa-razon-comercial').textContent = data.razon_comercial; // Razón Comercial
                    document.getElementById('telefono').textContent = data.telefono; // Teléfono
                    document.getElementById('correo').textContent = data.correo; // Correo Electrónico
                    document.getElementById('ubicacion').textContent = data.direccion; // Ubicación
                    document.getElementById('tipo-empresa').textContent = data.tipo_empresa; // Tipo de Empresa
                    document.getElementById('estado').textContent = data.estado; // Estado
                }
            })
            .catch(error => {
                console.error('Error al cargar los detalles de la empresa:', error);
            });
    }
});
