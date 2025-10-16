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

## 📁 Estructura del Proyecto

\`\`\`
VERTEX-INV/
├── BDD/
│   └── vertex_inv.sql          # Script de base de datos
├── iconos/                     # Iconos SVG del sistema
├── img/                        # Imágenes estáticas
├── modulos/
│   ├── controllers/            # Controladores PHP (CRUD)
│   ├── db/
│   │   └── conexion.php       # Conexión a base de datos
│   ├── helpers/               # Funciones auxiliares
│   │   ├── security.php       # Funciones de seguridad
│   │   └── upload.php         # Manejo de uploads
│   ├── img/                   # Imágenes de productos
│   ├── js/                    # Scripts JavaScript
│   │   ├── main.js           # Script principal
│   │   ├── productos.js      # Gestión de productos
│   │   ├── categorias.js     # Gestión de categorías
│   │   └── ...
│   ├── config.php            # Configuración general
│   ├── inicio.php            # Dashboard
│   ├── productos.php         # Vista de productos
│   ├── categorias.php        # Vista de categorías
│   └── ...
├── logs/                      # Logs de errores
├── .env.example.php          # Ejemplo de configuración
├── .env.php                  # Configuración (no subir a Git)
├── .gitignore               # Archivos ignorados por Git
├── .htaccess                # Configuración Apache
├── index.php                # Punto de entrada
├── style.css                # Estilos principales
├── README.md                # Este archivo
└── INSTRUCCIONES_HOSTINGER.md
\`\`\`

## 🔒 Seguridad

El sistema incluye las siguientes medidas de seguridad:

- ✅ Sanitización de inputs
- ✅ Prepared statements para prevenir SQL injection
- ✅ Validación de tipos de archivo en uploads
- ✅ Protección de archivos sensibles (.env.php, .htaccess)
- ✅ Headers de seguridad configurados
- ✅ Límites de tamaño de archivo
- ✅ Optimización automática de imágenes

## 🎨 Personalización

### Cambiar Colores
Editá las variables CSS en `style.css`:
\`\`\`css
:root{
    --background-color: white;
    --text-title-color: #053D4E;
    --text-color: #32363B;
    --icon-color: #32363B;
    /* ... más variables ... */
}
\`\`\`

### Cambiar Logo
Reemplazá el archivo `img/logovertex.jpg` con tu logo

### Modificar Nombre
Editá `index.php` y cambiá:
\`\`\`html
<span class="name">VERTEX</span>
\`\`\`

## 📊 Base de Datos

### Tablas Principales

- **productos**: Inventario de productos
- **categorias**: Categorías de productos
- **proveedores**: Información de proveedores
- **clientes**: Base de datos de clientes
- **pedidos**: Registro de pedidos

### Relaciones

- `productos.categoria_id` → `categorias.id_categ` (Foreign Key)

## 🐛 Solución de Problemas Comunes

### Error de conexión a la base de datos
- Verificá las credenciales en `.env.php`
- Asegurate de que MySQL esté corriendo
- Verificá que la base de datos exista

### Las imágenes no se cargan
- Verificá permisos de la carpeta `modulos/img/` (755 o 775)
- Asegurate de que la carpeta exista
- Revisá la configuración de `upload_max_filesize` en php.ini

### Error 500
- Revisá los logs en `logs/php-errors.log`
- Verificá que `.htaccess` sea compatible con tu servidor
- Asegurate de que mod_rewrite esté habilitado

### Páginas en blanco
- Activá el modo desarrollo en `.env.php`
- Revisá la consola del navegador (F12)
- Verificá los logs de PHP

## 📝 Changelog

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

## 👨‍💻 Desarrollo

### Agregar un Nuevo Módulo

1. Creá el archivo PHP en `modulos/nombre_modulo.php`
2. Creá el controlador en `modulos/controllers/`
3. Creá el script JS en `modulos/js/nombre_modulo.js`
4. Agregá el enlace en el sidebar de `index.php`
5. Actualizá `main.js` para cargar el script

### Convenciones de Código

- Usá nombres descriptivos en español argentino
- Comentá el código complejo
- Seguí la estructura MVC existente
- Usá prepared statements para consultas SQL
- Sanitizá todos los inputs del usuario

## 📞 Soporte

Para reportar bugs o solicitar features, creá un issue en el repositorio.

## 📄 Licencia

Este proyecto es de uso privado. Todos los derechos reservados.

---

Desarrollado con ❤️ para VERTEX
