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
    # 1. Abrir la página de creación de programas
    driver.get("http://localhost/senalink5/senalink5/senalink/html/Super_Admin/Programa_Formacion/CreatePrograma.php")
    driver.maximize_window()

    # 2. Esperar que cargue el formulario
    WebDriverWait(driver, 5).until(
        EC.presence_of_element_located((By.ID, "codigo"))
    )

    # 3. Completar campos del formulario
    driver.find_element(By.ID, "codigo").send_keys("10001")
    driver.find_element(By.ID, "ficha").send_keys("20001")

    Select(driver.find_element(By.ID, "nivel_formacion")).select_by_visible_text("TECNOLOGO")
    Select(driver.find_element(By.ID, "sector_programa")).select_by_visible_text("SERVICIOS")
    Select(driver.find_element(By.ID, "etapa_ficha")).select_by_visible_text("LECTIVA")
    Select(driver.find_element(By.ID, "sector_economico")).select_by_visible_text("CONSTRUCCIÓN")

    driver.find_element(By.ID, "nombre_programa").send_keys("Gestion de Proyectos de Construccion")
    driver.find_element(By.ID, "nombre_ocupacion").send_keys("Gestor de Proyectos")

    driver.find_element(By.ID, "duracion_programa").send_keys("1800")
    Select(driver.find_element(By.ID, "estado")).select_by_visible_text("En ejecucion")

    driver.find_element(By.ID, "fecha_finalizacion").send_keys("2027-06-30")

    # 4. Enviar formulario
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()

    # 5. Validar resultado (alert de éxito)
    try:
        WebDriverWait(driver, 10).until(
            EC.text_to_be_present_in_element(
                (By.CLASS_NAME, "alert"), "¡Programa creado exitosamente!"
            )
        )
        print(" Prueba exitosa: El programa fue creado correctamente.")
    except:
        print(" Prueba fallida: No se encontró el mensaje de éxito.")

    time.sleep(3)

finally:
    driver.quit()
