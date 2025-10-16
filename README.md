# 🏢 VERTEX - Sistema de Gestión de Inventario

Sistema completo de gestión de inventario desarrollado en PHP y MySQL, diseñado para pequeñas y medianas empresas.

## 📋 Características

- ✅ Gestión completa de productos con imágenes
- ✅ Control de categorías
- ✅ Administración de proveedores
- ✅ Gestión de clientes (minoristas y mayoristas)
- ✅ Sistema de pedidos con estados
- ✅ Búsqueda global en tiempo real
- ✅ Alertas de stock bajo
- ✅ Interfaz responsive (móvil y escritorio)
- ✅ Sistema de navegación SPA (Single Page Application)

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 8.x
- **Base de Datos**: MySQL / MariaDB
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Framework CSS**: Bootstrap 5.3
- **Iconos**: Bootstrap Icons

## 📦 Requisitos del Sistema

### Desarrollo Local
- PHP 8.0 o superior
- MySQL 5.7 o superior / MariaDB 10.4 o superior
- Apache con mod_rewrite habilitado
- Extensiones PHP requeridas:
  - mysqli
  - gd (para procesamiento de imágenes)
  - fileinfo

### Hosting (Hostinger)
- Plan de hosting con soporte PHP 8.x
- Base de datos MySQL
- Acceso FTP o File Manager
- Certificado SSL (recomendado)

## 🚀 Instalación

### Instalación Local (XAMPP/WAMP/MAMP)

1. **Clonar o descargar el proyecto**
   \`\`\`bash
   git clone [tu-repositorio]
   cd VERTEX-INV
   \`\`\`

2. **Configurar la base de datos**
   - Abrí phpMyAdmin (http://localhost/phpmyadmin)
   - Creá una nueva base de datos llamada `vertex_inv`
   - Importá el archivo `BDD/vertex_inv.sql`

3. **Configurar la conexión**
   - Copiá `.env.example.php` y renombralo a `.env.php`
   - Editá `.env.php` con tus credenciales locales:
   \`\`\`php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'vertex_inv');
   define('ENVIRONMENT', 'development');
   define('BASE_URL', '/VERTEX-INV');
   \`\`\`

4. **Configurar permisos**
   \`\`\`bash
   chmod 755 modulos/img
   chmod 755 logs
   \`\`\`

5. **Acceder al sistema**
   - Abrí tu navegador en: `http://localhost/VERTEX-INV`

### Instalación en Hostinger

Seguí las instrucciones detalladas en el archivo `INSTRUCCIONES_HOSTINGER.md`


## 🔒 Seguridad

El sistema incluye las siguientes medidas de seguridad:

- ✅ Sanitización de inputs
- ✅ Prepared statements para prevenir SQL injection
- ✅ Validación de tipos de archivo en uploads
- ✅ Protección de archivos sensibles (.env.php, .htaccess)
- ✅ Headers de seguridad configurados
- ✅ Límites de tamaño de archivo
- ✅ Optimización automática de imágenes

### Versión 2.0 (Optimizada para Hosting)
- ✅ Sistema de configuración con variables de entorno
- ✅ Funciones de seguridad mejoradas
- ✅ Optimización automática de imágenes
- ✅ Manejo de errores mejorado
- ✅ Configuración de caché y compresión
- ✅ Headers de seguridad
- ✅ Documentación completa

### Versión 1.0 (Inicial)
- ✅ Sistema básico de inventario
- ✅ CRUD de productos, categorías, clientes, proveedores
- ✅ Sistema de pedidos
- ✅ Búsqueda global


## 📄 Licencia

Este proyecto es de uso privado. Todos los derechos reservados.
