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
