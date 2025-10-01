from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
import time

# Inicializar driver Chrome
service = Service("C:/webdriver/chromedriver.exe")
driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()))

try:
    # 1. Abrir la página de detalle de empresa (con un id válido en la URL)
    driver.get("http://localhost/senalink5/senalink5/senalink/html/Super_Admin/Empresa/Empresa.html?id=77")
    driver.maximize_window()

    # 2. Esperar que cargue la info de la empresa
    WebDriverWait(driver, 5).until(
        EC.presence_of_element_located((By.ID, "btn-inhabilitar"))
    )

    # 3. Dar clic en "Inhabilitar"
    btn_inhabilitar = driver.find_element(By.ID, "btn-inhabilitar")
    btn_inhabilitar.click()

    # 4. Esperar a que aparezca el modal de confirmación
    WebDriverWait(driver, 5).until(
        EC.visibility_of_element_located((By.ID, "custom-confirm"))
    )

    # 5. Confirmar la acción en el modal
    confirm_yes = driver.find_element(By.ID, "confirm-yes")
    confirm_yes.click()

    # 6. Validar que el estado cambió a "Inhabilitada"
    estado = WebDriverWait(driver, 10).until(
        EC.text_to_be_present_in_element((By.ID, "estado"), "Inhabilitada")
    )

    if estado:
        print(" Prueba exitosa: La empresa fue inhabilitada correctamente.")
    else:
        print(" Prueba fallida: No se encontró el cambio de estado.")

    time.sleep(3)

finally:
    driver.quit()
