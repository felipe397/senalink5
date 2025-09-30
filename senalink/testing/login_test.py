from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time
from webdriver_manager.chrome import ChromeDriverManager

# Ruta al ChromeDriver
driver_path = "C:/webdriver/chromedriver.exe"

# Inicializar navegador
service = Service(driver_path)
driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()))
# 1. Abrir la página del login
driver.get("http://localhost/senalink5/senalink5/senalink/html/")  # ajusta la ruta

try:
    # 2. Seleccionar el rol (ejemplo: AdminSENA)
    rol = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.ID, "rol"))
    )
    rol.send_keys("super_admin")

    # 3. Llenar correo
    correo = driver.find_element(By.NAME, "correo")
    correo.send_keys("crisberx@gmail.com")

    # 4. Llenar contraseña
    contrasena = driver.find_element(By.NAME, "contrasena")
    contrasena.send_keys("ClaveSegura123")

    # 5. Hacer clic en Iniciar Sesión
    boton = driver.find_element(By.CLASS_NAME, "login__button")
    boton.click()

    # 6. Esperar redirección o mensaje
    time.sleep(3)  # espera básica
    print("Título después del login:", driver.title)

except Exception as e:
    print("Error durante la prueba:", e)

finally:
    driver.quit()
