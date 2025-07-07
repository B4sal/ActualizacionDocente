# 🚀 Guía de Instalación Completa

## 📋 Requisitos Previos

Antes de comenzar, asegúrate de tener instalados los siguientes componentes:

- **XAMPP** (Apache 2.4+, MySQL 5.7+, PHP 7.4+)
- **Navegador web** moderno (Chrome, Firefox, Edge)
- **Git** (opcional, para clonación)

## 🔧 Pasos de Instalación

### Paso 1: Descargar el Proyecto

Tienes dos opciones para obtener el proyecto:

#### Opción A: Clonar con Git
```bash
git clone https://github.com/B4sal/ActualizacionDocente.git
```

#### Opción B: Descargar ZIP
1. Ve al repositorio de GitHub
2. Haz clic en el botón **Code** (verde)
3. Selecciona **Download ZIP**

![Descarga desde GitHub](https://i.imgur.com/De9DpFt.png)

### Paso 2: Configurar el Entorno

1. **Mover los archivos a XAMPP**
   - Copia/mueve la carpeta del proyecto a: `C:\xampp\htdocs\ActualizacionDocente\`
   - Asegúrate de que la estructura de carpetas sea correcta

2. **Iniciar servicios de XAMPP**
   - Abre el **XAMPP Control Panel**
   - Haz clic en **Start** para **Apache**
   - Haz clic en **Start** para **MySQL**
   - Espera a que ambos servicios estén en verde

### Paso 3: Configurar la Base de Datos

#### 3.1 Crear la Base de Datos
1. Abre tu navegador web
2. Ve a: `http://localhost/phpmyadmin`
3. Haz clic en **New** (Nueva) en el panel izquierdo
4. Escribe el nombre: `actualizaciondocente`
5. Selecciona **utf8_general_ci** como collation
6. Haz clic en **Create**

![Crear Base de Datos](https://i.imgur.com/PWsUMNQ.png)

#### 3.2 Importar el Esquema
1. Selecciona la base de datos `actualizaciondocente` que acabas de crear
2. Haz clic en la pestaña **Import** (Importar)
3. Haz clic en **Choose File** (Elegir archivo)
4. Navega hasta: `ActualizacionDocente/sql/actualizaciondocenteDB.sql`
5. Selecciona el archivo y haz clic en **Open**
6. Haz clic en **Go** (Continuar) para importar

![Importar SQL](https://i.imgur.com/kMIgROa.png)

### Paso 4: Configurar la Conexión (Opcional)

Si necesitas modificar la configuración de la base de datos:

1. Abre el archivo: `config/conn.php`
2. Verifica que los datos coincidan con tu configuración:
   ```php
   $host = 'localhost';
   $username = 'root';
   $password = '';
   $database = 'actualizaciondocente';
   ```

### Paso 5: Probar la Instalación

1. Abre tu navegador web
2. Ve a: `http://localhost/ActualizacionDocente/`
3. Deberías ver la página principal del sistema

## ✅ Verificación de Instalación

### Checklist de Verificación
- [ ] Apache está ejecutándose (verde en XAMPP)
- [ ] MySQL está ejecutándose (verde en XAMPP)
- [ ] Base de datos `actualizaciondocente` creada
- [ ] Tablas importadas correctamente
- [ ] Página principal carga sin errores
- [ ] Puedes navegar por las diferentes secciones

### Páginas de Prueba
- **Dashboard**: `http://localhost/ActualizacionDocente/`
- **Cursos**: `http://localhost/ActualizacionDocente/administrar_cursos.php`
- **Períodos**: `http://localhost/ActualizacionDocente/administrar_periodos.php`
- **Profesores**: `http://localhost/ActualizacionDocente/registro_profesores.php`

## 🐛 Solución de Problemas Comunes

### Error: "Could not connect to database"
- **Causa**: MySQL no está ejecutándose o configuración incorrecta
- **Solución**: Verifica que MySQL esté activo en XAMPP y revisa `config/conn.php`

### Error: "Table doesn't exist"
- **Causa**: Base de datos no importada correctamente
- **Solución**: Repite el Paso 3.2 para importar el archivo SQL

### Error: "Access forbidden"
- **Causa**: Archivos no están en la carpeta correcta
- **Solución**: Verifica que los archivos están en `C:\xampp\htdocs\ActualizacionDocente\`

### Error: "Port 80 in use"
- **Causa**: Otro servicio está usando el puerto 80
- **Solución**: Cierra Skype, IIS u otros servicios que puedan usar el puerto 80

## 🔧 Configuración Avanzada

### Cambiar Puerto de Apache
Si el puerto 80 está ocupado:
1. Abre XAMPP Control Panel
2. Haz clic en **Config** junto a Apache
3. Selecciona **httpd.conf**
4. Busca `Listen 80` y cambia por `Listen 8080`
5. Busca `ServerName localhost:80` y cambia por `ServerName localhost:8080`
6. Guarda y reinicia Apache
7. Accede via: `http://localhost:8080/ActualizacionDocente/`

### Configurar PHP
Para verificar la configuración de PHP:
1. Crea un archivo `info.php` en la carpeta del proyecto
2. Añade el contenido: `<?php phpinfo(); ?>`
3. Accede a: `http://localhost/ActualizacionDocente/info.php`
4. Verifica que PHP 7.4+ esté activo

## 📞 Soporte

Si tienes problemas durante la instalación:
1. Revisa esta guía paso a paso
2. Consulta la [documentación adicional](../README.md)
3. Verifica que todos los requisitos estén cumplidos
4. Revisa los logs de error en XAMPP

---

<div align="center">
  <p><strong>¡Instalación completada exitosamente! 🎉</strong></p>
  <p>Ahora puedes comenzar a usar el Sistema de Actualización Docente</p>
</div>
