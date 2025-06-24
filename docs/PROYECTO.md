# Sistema de Actualización Docente

## 📋 Descripción del Proyecto

El **Sistema de Actualización Docente** es una aplicación web desarrollada para el **Instituto Tecnológico de Mérida** que permite gestionar la información de cursos, períodos académicos y el registro de profesores para diferentes materias y horarios.

## 🎯 Objetivo

Facilitar la administración y registro de profesores en cursos específicos durante diferentes períodos académicos, proporcionando una interfaz intuitiva para la gestión de horarios y asignaciones docentes.

## 🏗️ Arquitectura del Sistema

### Frontend
- **HTML5** con diseño responsivo
- **Bootstrap 5.3.3** para la interfaz de usuario
- **jQuery 3.6.0** para interacciones dinámicas
- **JavaScript** para funcionalidades del lado del cliente

### Backend
- **PHP** para la lógica del servidor
- **MySQL/MariaDB** como sistema de gestión de base de datos
- **XAMPP** como entorno de desarrollo local

## 🗄️ Estructura de la Base de Datos

### Tablas Principales

#### 1. `cursos`
- `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
- `curso` (VARCHAR(100)) - Nombre del curso
- `status` (VARCHAR(2)) - Estado: 'A' (Activo) / 'I' (Inactivo)

#### 2. `periodo`
- `id_periodo` (INT, AUTO_INCREMENT, PRIMARY KEY)
- `nombre_periodo` (VARCHAR(15)) - Ejemplo: 'Ago-Dic-2024-1'

#### 3. `horario_cursos`
- `id_curso` (INT, FOREIGN KEY)
- `num` (INT) - Número secuencial del horario
- `horario` (VARCHAR(20)) - Descripción del horario

#### 4. `encabezado`
- `id_curso` (INT, FOREIGN KEY)
- `periodo` (VARCHAR(15), FOREIGN KEY)
- `curso` (VARCHAR(50)) - Nombre del curso
- `horario` (VARCHAR(20)) - Horario del curso
- `no_profesores` (INT) - Número de profesores asignados

#### 5. `registro`
- `id_periodo` (VARCHAR(15), FOREIGN KEY)
- `id_curso` (INT, FOREIGN KEY)
- `id_profesor` (INT) - ID único del profesor por curso/período
- `expediente` (VARCHAR(10)) - Número de expediente del profesor
- `nombre` (VARCHAR(100)) - Nombre completo del profesor
- `correo` (VARCHAR(100)) - Correo electrónico
- `id_horario_curso` (INT, FOREIGN KEY) - Referencia al horario específico

## 📁 Estructura de Archivos

### Archivos HTML (Frontend)
- `index.html` - Página principal para crear encabezados de curso
- `cursos.html` - Visualización de cursos y sus horarios
- `periodos.html` - Gestión de períodos académicos
- `administrarcursos.html` - Administración de cursos
- `administrarperiodos.html` - Administración de períodos
- `registro_de_profe.html` - Registro de profesores

### Archivos PHP (Backend)
- `conn.php` - Configuración de conexión a la base de datos
- `get_cursos.php` - Obtener lista de cursos
- `get_periodos.php` - Obtener lista de períodos
- `get_horarios.php` - Obtener horarios por curso
- `get_encabezados.php` - Obtener encabezados de curso
- `get_registro.php` - Obtener registros de profesores
- `save_curso.php` - Guardar nuevo curso
- `save_encabezado.php` - Guardar encabezado de curso
- `save_profesor.php` - Registrar profesor
- `edit_curso.php` - Editar curso existente
- `delete_curso.php` - Eliminar curso
- `add_periodo.php` - Agregar período
- `update_periodo.php` - Actualizar período
- `delete_periodo.php` - Eliminar período
- `add_horario.php` - Agregar horario
- `delete_horario.php` - Eliminar horario

### Archivos de Base de Datos
- `actualizaciondocenteDB.sql` - Script de creación e inicialización de la base de datos

### Documentación
- `README.md` - Información de licencia y términos de uso
- `DatosDumb.md` - Datos de prueba
- `QuerysCreacionDB.md` - Consultas de creación de base de datos

## 🚀 Funcionalidades Principales

### 1. Gestión de Cursos
- ✅ Crear nuevos cursos
- ✅ Visualizar cursos existentes con sus horarios
- ✅ Editar información de cursos
- ✅ Activar/Desactivar cursos
- ✅ Eliminar cursos

### 2. Gestión de Períodos
- ✅ Crear períodos académicos
- ✅ Visualizar períodos disponibles
- ✅ Editar períodos existentes
- ✅ Eliminar períodos

### 3. Gestión de Horarios
- ✅ Asignar horarios específicos a cursos
- ✅ Visualizar horarios por curso
- ✅ Editar horarios existentes
- ✅ Eliminar horarios

### 4. Registro de Profesores
- ✅ Registrar profesores en cursos específicos
- ✅ Asignar horarios a profesores
- ✅ Visualizar registros por período y curso
- ✅ Validación de datos de entrada
- ✅ Generación automática de IDs de profesor

### 5. Encabezados de Curso
- ✅ Crear encabezados que vinculan cursos, períodos y horarios
- ✅ Especificar número de profesores requeridos
- ✅ Gestión automática de relaciones entre tablas

## 🛠️ Instalación y Configuración

### Requisitos Previos
- XAMPP (Apache, MySQL, PHP)
- Navegador web moderno
- Editor de código (opcional)

### Pasos de Instalación

1. **Clonar/Descargar el proyecto**
   ```bash
   # Colocar los archivos en la carpeta htdocs de XAMPP
   C:\xampp\htdocs\ActualizacionDocente\
   ```

2. **Iniciar servicios XAMPP**
   - Apache
   - MySQL

3. **Crear la base de datos**
   - Acceder a phpMyAdmin (http://localhost/phpmyadmin)
   - Crear base de datos llamada `actualizaciondocente`
   - Importar el archivo `actualizaciondocenteDB.sql`

4. **Configurar conexión**
   - Verificar configuración en `conn.php`
   ```php
   $enlace = mysqli_connect("localhost", "root", "", "actualizaciondocente");
   ```

5. **Acceder al sistema**
   - Abrir navegador y ir a: `http://localhost/ActualizacionDocente/`

## 🔄 Flujo de Trabajo

### Flujo Principal de Uso

1. **Configuración Inicial**
   - Crear períodos académicos
   - Crear cursos disponibles
   - Definir horarios para cada curso

2. **Creación de Encabezados**
   - Seleccionar período y curso
   - Definir horario general
   - Especificar número de profesores

3. **Registro de Profesores**
   - Seleccionar período y curso
   - Elegir horario específico
   - Ingresar datos del profesor
   - Guardar registro

4. **Consulta y Administración**
   - Visualizar cursos con sus horarios
   - Consultar registros por período/curso
   - Administrar datos existentes

## 🔐 Características de Seguridad

- ✅ Uso de prepared statements para prevenir inyección SQL
- ✅ Validación de datos en frontend y backend
- ✅ Transacciones de base de datos para mantener integridad
- ✅ Manejo de errores y excepciones
- ✅ Restricciones de clave foránea en la base de datos

## 📱 Responsive Design

El sistema está diseñado para funcionar en diferentes dispositivos:
- 📱 Móviles
- 📱 Tablets
- 💻 Computadoras de escritorio

Utiliza Bootstrap 5.3.3 para garantizar una experiencia responsiva y moderna.

## 🎨 Interfaz de Usuario

### Características de Diseño
- **Navbar responsiva** con offcanvas para dispositivos móviles
- **Formularios intuitivos** con validación en tiempo real
- **Tablas dinámicas** para visualización de datos
- **Alertas informativas** para feedback del usuario
- **Acordeones expandibles** para organización de contenido
- **Botones de acción** claramente identificados

### Colores y Branding
- Logo oficial del Instituto Tecnológico de Mérida
- Esquema de colores institucional
- Tipografía legible y profesional

## 🔄 APIs Internas

### Endpoints PHP

#### Consulta de Datos
- `GET get_cursos.php` - Lista de cursos
- `GET get_periodos.php` - Lista de períodos
- `GET get_horarios.php?id_curso=X` - Horarios por curso
- `GET get_registro.php?periodo=X&curso=Y` - Registros filtrados

#### Operaciones CRUD
- `POST save_curso.php` - Crear curso
- `POST save_encabezado.php` - Crear encabezado
- `POST save_profesor.php` - Registrar profesor
- `POST edit_curso.php` - Editar curso
- `POST delete_curso.php` - Eliminar curso

## 📊 Base de Datos de Ejemplo

### Datos Precargados
- **Cursos**: Matemáticas, Física, Química, Matemáticas 4
- **Períodos**: Ago-Dic-2024-1, Ene-Jun-2025-1, Ago-Dic-2025-2
- **Profesores**: Juan Pérez, María López, Carlos Díaz, Ana Torres
- **Horarios**: Diversos rangos horarios para cada curso

## 🚧 Futuras Mejoras

### Funcionalidades Potenciales
- 📧 Sistema de notificaciones por email
- 📊 Reportes y estadísticas avanzadas
- 👥 Sistema de roles y permisos
- 📱 Aplicación móvil nativa
- 🔍 Búsqueda avanzada y filtros
- 📄 Exportación de datos (PDF, Excel)
- 🔄 Integración con sistemas institucionales

### Optimizaciones Técnicas
- ⚡ Implementación de caché
- 🔐 Autenticación y autorización robusta
- 📝 Logging detallado del sistema
- 🧪 Suite de pruebas automatizadas
- 🔄 API REST para integraciones

## 👥 Créditos

**Desarrollado para:** Instituto Tecnológico de Mérida  
**Propósito:** Actualización y gestión docente  
**Tecnologías:** PHP, MySQL, HTML5, Bootstrap 5, jQuery  

## 📄 Licencia

Este proyecto es propiedad del **Instituto Tecnológico de Mérida**. Todos los derechos reservados. Ver `README.md` para términos completos de uso y licencia.

---

*Documentación actualizada: Junio 2025*
