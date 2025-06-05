# Proyecto Vue + Laravel

Este proyecto es una aplicación web moderna construida con Laravel y Vue.js, utilizando Tailwind CSS para el diseño y Vite como bundler.

## 🚀 Tecnologías Principales

- **Backend**: Laravel (PHP)
- **Frontend**: Vue.js 3
- **CSS Framework**: Tailwind CSS
- **Bundler**: Vite
- **Base de datos**: MySQL (XAMPP)

## 📋 Requisitos Previos

- PHP >= 8.1
- Composer
- Node.js >= 16.x
- MySQL
- XAMPP (o servidor web similar)

## 🛠️ Instalación

1. **Clonar el repositorio**
   ```bash
   git clone [URL_DEL_REPOSITORIO]
   cd proyecto-vue
   ```

2. **Instalar dependencias de PHP**
   ```bash
   composer install
   ```

3. **Instalar dependencias de Node.js**
   ```bash
   npm install
   ```

4. **Configurar el entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurar la base de datos**
   - Crear una base de datos en MySQL
   - Configurar las credenciales en el archivo `.env`
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tu_base_de_datos
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
   ```

6. **Ejecutar migraciones**
   ```bash
   php artisan migrate
   ```

7. **Compilar assets**
   ```bash
   npm run dev
   ```

## 🚀 Desarrollo

Para iniciar el servidor de desarrollo:

```bash
# Terminal 1 - Servidor Laravel
php artisan serve

# Terminal 2 - Servidor Vite
npm run dev
```

## 🏗️ Construcción para Producción

```bash
npm run build
```

## 📁 Estructura del Proyecto

- `/app` - Código principal de Laravel
- `/resources` - Assets y componentes Vue
- `/database` - Migraciones y seeders
- `/routes` - Definición de rutas
- `/config` - Archivos de configuración
- `/public` - Archivos públicos
- `/storage` - Archivos de almacenamiento

## 🔧 Comandos Útiles

- `php artisan serve` - Inicia el servidor de desarrollo
- `php artisan migrate` - Ejecuta las migraciones
- `php artisan migrate:fresh` - Reinicia la base de datos
- `npm run dev` - Inicia el servidor de desarrollo de Vite
- `npm run build` - Compila los assets para producción

## 📝 Notas Adicionales

- Asegúrate de tener los permisos correctos en las carpetas `storage` y `bootstrap/cache`
- Para desarrollo local, se recomienda usar XAMPP o un entorno similar
- Mantén actualizadas las dependencias con `composer update` y `npm update`

## 🤝 Contribución

1. Fork el proyecto
2. Crea tu rama de características (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE.md](LICENSE.md) para más detalles.

# Sistema de Registro de Piezas

## Estructura de la Base de Datos

### 1. Tabla de Usuarios (usuarios)
```sql
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    username VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```
**Justificación**: 
- Se mantiene una tabla de usuarios para control de acceso y auditoría
- El campo `username` es único para evitar duplicados
- Se almacena el nombre completo para mejor identificación

### 2. Tabla de Proyectos (projects)
```sql
CREATE TABLE projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo_proyecto VARCHAR(255) UNIQUE,
    nombre VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
);
```

- Se implementó soft delete (`deleted_at`) para mantener historial

### 3. Tabla de Bloques (blocks)
```sql
CREATE TABLE blocks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo_bloque VARCHAR(255) UNIQUE,
    project_id INT,
    nombre VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);
```
- Se implementó soft delete para mantener historial

### 4. Tabla de Piezas (pieces)
```sql
CREATE TABLE pieces (
    id INT PRIMARY KEY AUTO_INCREMENT,
    block_id INT,
    codigo_pieza VARCHAR(255) UNIQUE,
    nombre VARCHAR(255),
    peso_teorico DECIMAL(8,2),
    usuario_id INT,
    estado ENUM('Pendiente', 'Fabricada', 'En_Proceso') DEFAULT 'Pendiente',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (block_id) REFERENCES blocks(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
```
- Se agregó `estado` como ENUM para controlar el estado de fabricación
- Se relaciona con bloques y usuarios
- Se incluye `peso_teorico` como decimal para mayor precisión

### 5. Tabla de Registros de Piezas (piece_records)
```sql
CREATE TABLE piece_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pieza_id INT,
    peso_real DECIMAL(8,2),
    diferencia DECIMAL(8,2),
    fecha_hora TIMESTAMP,
    usuario_id INT,
    observaciones TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (pieza_id) REFERENCES pieces(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
```
**Justificación**:
- Esta tabla es crucial para el seguimiento y auditoría
- Permite mantener un historial completo de los registros de peso
- Calcula automáticamente la diferencia entre peso teórico y real
- Registra quién realizó cada medición y cuándo
- Permite agregar observaciones para casos especiales

## Mejoras Implementadas

1. **Auditoría**: Todas las tablas incluyen timestamps para seguimiento de cambios
2. **Integridad Referencial**: Se implementaron claves foráneas con CASCADE para mantener la integridad
3. **Soft Delete**: En proyectos y bloques para mantener historial
4. **Precisión**: Uso de DECIMAL para pesos
5. **Trazabilidad**: Registro de usuarios y fechas en todas las operaciones