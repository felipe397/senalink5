from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
import time

# Inicializar driver Chrome
driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()))

try:
    # 1. Abrir la página de edición con el ID de la empresa
    driver.get("http://localhost/senalink5/senalink5/senalink/html/Super_Admin/Empresa/EmpresaEdit.html?id=77")
    driver.maximize_window()

    # 2. Esperar que los datos se carguen (ejemplo: campo representante)
    WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.ID, "representante_legal"))
    )

    # 3. Editar algunos campos
    representante = driver.find_element(By.ID, "representante_legal")
    representante.clear()
    representante.send_keys("Carlos López")

    telefono = driver.find_element(By.ID, "telefono")
    telefono.clear()
    telefono.send_keys("3129876543")

    correo = driver.find_element(By.ID, "correo")
    correo.clear()
    correo.send_keys("nuevaempresa@ejemplo.com")

    barrio = driver.find_element(By.ID, "barrio")
    barrio.clear()
    barrio.send_keys("San José")

    # 4. Guardar cambios
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()

    # 5. Validar resultado
    try:
        WebDriverWait(driver, 10).until(
            EC.text_to_be_present_in_element(
                (By.CLASS_NAME, "alert"), "Datos actualizados correctamente"
            )
        )
        print(" Prueba exitosa: La empresa fue actualizada correctamente.")
    except:
        print(" Prueba fallida: No se encontró el mensaje de éxito.")

    time.sleep(3)

finally:
    driver.quit()
