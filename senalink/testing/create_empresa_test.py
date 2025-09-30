from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import Select, WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
import time

# Inicializar driver Chrome
service = Service("C:/webdriver/chromedriver.exe")
driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()))

try:
    # 1. Abrir la página del formulario
    driver.get("http://localhost/senalink5/senalink5/senalink/html/Empresa/CreateEmpresa.html")
    driver.maximize_window()

    # 2. Completar campos del formulario
    driver.find_element(By.ID, "nit").send_keys("783793124")
    driver.find_element(By.ID, "representante").send_keys("Juan Perez")
    driver.find_element(By.ID, "razon").send_keys("Empresa Ejemplo S.A.S")
    driver.find_element(By.ID, "telefono").send_keys("3001234567")
    driver.find_element(By.ID, "correo").send_keys("empresa@ejemplo.com")
    driver.find_element(By.ID, "direccion").send_keys("Carrera 4 #24-52")
    driver.find_element(By.ID, "barrio").send_keys("Centro")

    # Seleccionar departamento (ejemplo: Antioquia)
    departamento_select = Select(driver.find_element(By.ID, "departamento"))
    departamento_select.select_by_visible_text("Antioquia")

    # Esperar que se carguen las ciudades dinámicamente (puedes ajustar el tiempo o usar espera explícita)
    WebDriverWait(driver, 5).until(
        EC.presence_of_all_elements_located((By.CSS_SELECTOR, "#ciudad option"))
    )

    # Seleccionar una o varias ciudades (ejemplo: Medellín y Bello)
    ciudades_select = Select(driver.find_element(By.ID, "ciudad"))
    ciudades_select.select_by_visible_text("Medellín")
    ciudades_select.select_by_visible_text("Bello")

    # Seleccionar Sector Económico (Industrial)
    tipo_empresa_select = Select(driver.find_element(By.ID, "tipo_empresa"))
    tipo_empresa_select.select_by_visible_text("Industrial")

    driver.find_element(By.ID, "contrasena").send_keys("SuperClaveSegura123*")

    # 3. Enviar formulario
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()

    # 4. Validar resultado
    try:
        # Esperar que aparezca el alert de éxito
        WebDriverWait(driver, 10).until(
            EC.text_to_be_present_in_element(
                (By.CLASS_NAME, "alert"), "Empresa creada exitosamente"
            )
        )
        print("✅ Prueba exitosa: La empresa fue creada correctamente.")
    except:
        print("❌ Prueba fallida: No se encontró el mensaje de éxito.")

    time.sleep(3)

finally:
    driver.quit()
