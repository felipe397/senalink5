document.addEventListener("DOMContentLoaded", function () {
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
                    <a href="Empresa.html?id=${empresa.id}">
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
                    document.querySelector('p:nth-of-type(1) span').textContent = data.nit; // Muestra el NIT
                    document.querySelector('p:nth-of-type(2) span').textContent = data.representante_legal; // Representante Legal
                    document.querySelector('p:nth-of-type(3) span').textContent = data.razon_social; // Razón Social
                    document.querySelector('p:nth-of-type(4) span').textContent = data.telefono; // Teléfono
                    document.querySelector('p:nth-of-type(5) span').textContent = data.correo; // Correo Electrónico
                    document.querySelector('p:nth-of-type(6) span').textContent = data.direccion; // Ubicación
                    document.querySelector('p:nth-of-type(7) span').textContent = data.tipo_empresa; // Tipo de Empresa
                    document.querySelector('p:nth-of-type(8) span').textContent = data.estado; // Estado

                    // Actualizar el enlace "Actualizar" para que incluya el id en la URL
                    const actualizarLink = document.querySelector('a.buttons__crud[href="EmpresaEdit.html"]');
                    if (actualizarLink) {
                        actualizarLink.href = `EmpresaEdit.html?id=${empresaId}`;
                    }
                }
            })
            .catch(error => {
                console.error('Error al cargar los detalles de la empresa:', error);
            });
    }
});
