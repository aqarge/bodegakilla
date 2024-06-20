import subprocess
import os
import time
import webbrowser

# Ruta a la carpeta del proyecto Laravel
laravel_project_path = r'C:\xampp\htdocs\bodegakilla'


# Comando para ejecutar 'php artisan serve'
php_serve_command = 'php artisan serve'

# Comandos para iniciar Apache y MySQL usando XAMPP
xampp_start_command = r'C:\xampp\xampp_start.exe'  # Cambia esto a la ruta de tu script de inicio de XAMPP

# URL para abrir en el navegador
url = 'http://127.0.0.1:8000/admin/login'

# Ejecutar XAMPP para iniciar Apache y MySQL
print("Iniciando Apache y MySQL con XAMPP...")
xampp_process = subprocess.Popen(xampp_start_command, shell=True)

# Esperar unos segundos para asegurarse de que Apache y MySQL estén en funcionamiento
time.sleep(10)

# Cambiar al directorio del proyecto Laravel y ejecutar 'php artisan serve'
print(f"Cambiando al directorio {laravel_project_path} y ejecutando 'php artisan serve'...")
os.chdir(laravel_project_path)
serve_process = subprocess.Popen(php_serve_command, shell=True)

# Esperar unos segundos para asegurarse de que el servidor Laravel esté en funcionamiento
time.sleep(5)

# Abrir la URL en el navegador predeterminado
print(f"Abrir la URL {url} en el navegador predeterminado...")
webbrowser.open(url)


# Esperar a que el programa termine
program_process.wait()

# Cuando el programa termine, finalizar el proceso de 'php artisan serve'
print("Terminando 'php artisan serve'...")
serve_process.terminate()

print("Comandos ejecutados exitosamente.")
