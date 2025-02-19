# Proyecto Laravel - Palomeras-Vallecas

## 📌 Descripción
Este proyecto ha sido desarrollado por estudiantes de Palomeras-Vallecas, con un enfoque en el **back-end** utilizando Laravel. Se han implementado diversas funcionalidades, como plantillas Blade, llamadas AJAX, migraciones, seeders, enrutamientos y relaciones **1:N y N:M**. Además, se han aplicado algunos estilos de Bootstrap para mejorar la presentación.

## 👥 Equipo de Desarrollo
- **Jorge Atienza**
- **Mario Alcaide**
- **Sergio Cerrada**
- **Hernán Uña**

## 🚀 Tecnologías Utilizadas
- **Laravel** (Blade Templates, Migraciones, Seeders, Enrutamiento)
- **AJAX** para comunicación con el servidor
- **Bootstrap** para estilos básicos
- **Relaciones de bases de datos** (1:N y N:M)

## 🛠 Configuración y Uso

### 📺 Archivos y Carpetas Necesarios
Para ejecutar correctamente el proyecto, asegúrate de contar con:
- La carpeta `vendor` (instalada con Composer)
- La carpeta `storage` dentro de `public`
- Las carpetas necesarias dentro de `storage`
- Un archivo `.env` configurado con la conexión a la base de datos

### 🛠 Pasos para Configurar el Proyecto
1. Clonar el repositorio o descargar los archivos del proyecto.
2. Instalar dependencias con Composer:
   ```bash
   composer install
   ```
3. Configurar el archivo `.env` con los datos de la base de datos.
4. Ejecutar migraciones y seeders (opcional):
   ```bash
   php artisan migrate --seed
   ```
5. Crear el enlace simbólico para el almacenamiento:
   ```bash
   php artisan storage:link
   ```
6. Iniciar el servidor de desarrollo:
   ```bash
   php artisan serve
   ```
7. Acceder a la aplicación en `http://127.0.0.1:8000`.

## 📝 Licencia
Este proyecto ha sido desarrollado con fines educativos y no cuenta con una licencia específica. Puede usarlo quien quiera sin mencionar el autor

---
💪 **¡Listo! Ahora puedes usar y modificar el proyecto según tus necesidades.**
