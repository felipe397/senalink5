from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import Select, WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
import time

# Inicializar driver Chrome
service = Service("C:/webdriver/chromedriver.exe")
driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()))

try:
    # 1. Abrir la página de edición de un programa existente
    driver.get("http://localhost/senalink5/senalink5/senalink/html/Super_Admin/Programa_Formacion/ProgramaEdit.html?id=175")
    driver.maximize_window()

    # 2. Esperar que cargue el formulario con datos ya llenos
    WebDriverWait(driver, 10).until(
        lambda d: d.find_element(By.ID, "codigo").get_attribute("value") != ""
    )


    # Duración
    duracion = driver.find_element(By.ID, "duracion_programa")
    duracion.clear()
    duracion.send_keys("2200")

    # Estado
    Select(driver.find_element(By.ID, "estado")).select_by_visible_text("Finalizado")

    # Fecha de finalización
    fecha_finalizacion = driver.find_element(By.ID, "fecha_finalizacion")
    fecha_finalizacion.clear()
    fecha_finalizacion.send_keys("2029-12-15")

    # Habilitar campos no editables para que se envíen en el formulario
    driver.execute_script("""
        document.getElementById('codigo').disabled = false;
        document.getElementById('ficha').disabled = false;
        document.getElementById('nivel_formacion').disabled = false;
        document.getElementById('sector_programa').disabled = false;
        document.getElementById('etapa_ficha').disabled = false;
        document.getElementById('sector_economico').disabled = false;
        document.getElementById('nombre_programa').disabled = false;
    """)

    # 3. Enviar formulario
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()

    # 4. Validar resultado (alert de éxito)
    try:
        WebDriverWait(driver, 10).until(
            EC.text_to_be_present_in_element(
                (By.CLASS_NAME, "alert"), "¡Programa actualizado exitosamente!"
            )
        )
        print(" Prueba exitosa: El programa fue actualizado correctamente.")
    except:
        print(" Prueba fallida: No se encontró el mensaje de éxito.")

    time.sleep(3)

finally:
    driver.quit()
