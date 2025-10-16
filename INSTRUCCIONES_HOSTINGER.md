# 📦 Instrucciones para Subir a Hostinger

## 🔧 Preparación Previa

### 1. Crear la Base de Datos en Hostinger

1. Ingresá al panel de control de Hostinger (hPanel)
2. Andá a **Bases de Datos MySQL**
3. Creá una nueva base de datos:
   - Nombre: `vertex_inv` (o el que prefieras)
   - Usuario: Creá un usuario nuevo o usá uno existente
   - Contraseña: Guardá bien esta contraseña
4. Anotá estos datos:
   - **Host**: generalmente es `localhost`
   - **Nombre de la base de datos**
   - **Usuario**
   - **Contraseña**

### 2. Importar la Base de Datos

1. En el panel de Hostinger, andá a **phpMyAdmin**
2. Seleccioná tu base de datos
3. Hacé click en **Importar**
4. Subí el archivo `BDD/vertex_inv.sql`
5. Hacé click en **Continuar**

## 📁 Subir los Archivos

### Opción A: Subir el Contenido Directamente (Recomendado)

1. Conectate por FTP o usá el Administrador de Archivos de Hostinger
2. Andá a la carpeta `public_html`
3. Subí **TODO EL CONTENIDO** de la carpeta VERTEX-INV (no la carpeta en sí, sino su contenido):
   - index.php
   - style.css
   - modulos/
   - iconos/
   - img/
   - .htaccess
   - etc.

### Opción B: Subir la Carpeta Completa

1. Subí la carpeta VERTEX-INV completa dentro de `public_html`
2. Tu sitio será accesible en: `http://tudominio.com/VERTEX-INV/`

## ⚙️ Configuración

### 1. Configurar la Conexión a la Base de Datos

1. Copiá el archivo `.env.example.php` y renombralo a `.env.php`
2. Editá `.env.php` con los datos de tu base de datos de Hostinger:

\`\`\`php
define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario_hostinger');
define('DB_PASS', 'tu_contraseña_hostinger');
define('DB_NAME', 'vertex_inv');
define('ENVIRONMENT', 'production');
\`\`\`

3. Si subiste el contenido directamente a public_html:
\`\`\`php
define('BASE_URL', '');
\`\`\`

4. Si subiste la carpeta VERTEX-INV completa:
\`\`\`php
define('BASE_URL', '/VERTEX-INV');
\`\`\`

### 2. Crear Carpeta de Logs (Opcional)

\`\`\`bash
mkdir logs
chmod 755 logs
\`\`\`

### 3. Verificar Permisos

Asegurate de que estas carpetas tengan permisos de escritura (755 o 775):
- `modulos/img/` (para subir imágenes de productos)
- `logs/` (para logs de errores)

## 🔒 Seguridad

### Habilitar HTTPS (SSL)

1. En el panel de Hostinger, andá a **SSL**
2. Activá el certificado SSL gratuito de Let's Encrypt
3. Una vez activado, descomentá estas líneas en `.htaccess`:

```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
