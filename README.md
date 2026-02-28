# ALMAMIA FRAGANCIAS

# Plataforma Interna

## Descripción General

**Alma Mia** es una plataforma completa para gestionar el **catálogo**, las **jerarquías comerciales**, los **pedidos**, las **campañas**, las **bonificaciones**, el **puntaje**, las **vendedoras**, líderes y coordinadoras, y todo el flujo operativo de la empresa Alma Mía Fragancias.

**D**esarrollada en **Laravel 12**, basada en el framework SaaS **Wave** de DevDojo e integrada con la pila **TALL** (TailwindCSS, Alpine.js, Livewire 3 y Volt). Está diseñada para gestionar el catálogo, los pedidos, las campañas, los puntajes y la estructura completa de vendedoras, líderes y coordinadoras.

Toda la interfaz pública está construida con **temas intercambiables** (Wave Themes) dentro de `resources/themes`, cada página se resuelve con Laravel **Folio**, y los componentes dinámicos usan **Volt**.\

La plataforma combina:

* Wave como base 
* Filament como panel administrativo
* Livewire + Volt como motor interactivo
* Laravel Folio para ruteo basado en archivos
* Temas personalizados para la interfaz pública
* Sin editar Routes por ningun motivo
---

# 🎯 **Objetivo del Sistema**

El propósito es que Alma Mia tenga una plataforma sólida que permita:

* Tener un **catálogo de productos oficial** siempre actualizado.
* Administrar **puntos**, **premios**, **bonificaciones**, categorías y reglas de campaña.
* Gestionar las jerarquías comerciales: **Vendedoras → Líderes → Coordinadoras**.
* Crear, editar y seguir **pedidos completos**, con su estructura comercial y monetaria.
* Controlar campañas, costos operativos, envíos, POP, bonificaciones y estados.
* Facilitar el crecimiento de la red comercial y evitar errores de jerarquía.
* Integrarse a futuro con **IA, automatizaciones, Apps móviles y POS Volt**.

---

## Arquitectura Técnica

| Capa                 | Herramientas              |
| -------------------- | ------------------------- |
| Backend              | Laravel 12 + Wave         |
| Frontend             | TailwindCSS               |
| Interactividad       | Livewire 3 + Volt         |
| JS reactivo          | Alpine.js                 |
| Panel administrativo | Filament v3/v4            |
| Ruteo                | Laravel Folio             |
| Autenticación        | Wave Auth + 2FA           |
| Roles/Permisos       | Spatie Laravel Permission |
| Temas                | Wave Themes (sin Vite)    |
| API                  | JWT (tymon/jwt-auth)      |
| Base de datos        | MySQL/MariaDB             |

---

## Dependencias

Listado de librerías principales utilizadas en el backend y frontend, con sus versiones base según `composer.json` y `package.json`.

### Backend

* **Laravel 12** (`laravel/framework` ^12.18)
* **Wave** (`devdojo/app` 0.11.0 + `devdojo/themes` 0.0.11)
* **Filament v4** (`filament/filament` ^4.0, `filament/tables` ^4.0)
* **Livewire 3** (`livewire/livewire` ^3.5)
* **Laravel Folio** (`laravel/folio` ^1.1)
* **Spatie Laravel Permission** (`spatie/laravel-permission` ^6.12)
* **JWT Auth** (`tymon/jwt-auth` ^2.2)
* **Stripe PHP** (`stripe/stripe-php` ^17.3)
* **Impersonate** (`lab404/laravel-impersonate` ^1.7.5)
* **Tailwind Merge Laravel** (`gehrisandro/tailwind-merge-laravel` ^1.3)
* **Blade Phosphor Icons** (`codeat3/blade-phosphor-icons` ^2.0)

### Frontend

* **Tailwind CSS** (`tailwindcss` ^4.1.12)
* **Alpine.js** (`alpinejs` ^3.4.2)
* **Axios** (`axios` ^1.8.2)
* **Plugins Tailwind** (`@tailwindcss/forms`, `@tailwindcss/typography`, `@tailwindcss/postcss`, `@tailwindcss/vite` ^4.1.12)
* **Sin Vite ni bundlers**: los assets del tema se administran como archivos estáticos.

## Características Principales

### Autenticación y funcionalidades de Wave

Wave provee toda la estructura base  incluyendo:

* Autenticación y registro.
* Recuperación de contraseña.
* Two Factor Auth (2FA).
* Perfiles editables.
* Impersonación de usuarios.
* Planes y suscripciones.
* Facturación con Stripe.
* Notificaciones.
* Blog, páginas y changelog.
* API interna.
* Sistema de temas.
* Plugins y extensiones.

- **Vistas públicas** (catálogo, home, productos, categorías).
- **Zona de usuarias** (vendedoras, líderes, coordinadoras).
- **Panel administrativo Filament** para el equipo interno.
- **Temas con frontend propio** totalmente independiente del panel.

---

# **Funcionalidades principales**

## ✔️ Autenticación completa (by Wave)

Wave proporciona:

* Login
* Registro
* Recuperar contraseña
* Verificación en dos pasos (2FA)
* Perfiles editables
* Impersonación (admin → usuaria)
* Planes, suscripciones, facturación Stripe
* Temas y landing públic

## Onboarding Comercial

Luego del registro, la usuaria debe completar un **modal de onboarding** obligatorio.

### Flujo:

1. Seleccionar rol:

   * **Vendedora**
   * **Líder**
2. Asignación automática del rol con Spatie.
3. Selección del referente:

   * Vendedora → elige líder.
   * Líder → elige coordinadora.
4. Validación del rol y existencia real del referente.
5. Se guardan relaciones en el modelo `User`:

   * `lider_id`
   * `coordinadora_id`
6. El sistema completa automáticamente la jerarquía en cada pedido.

---

## Panel Administrativo `/admin` (Filament)

Incluye:

* Gestión de **usuarios**, roles y permisos
* Blog, páginas, changelog
* Configuración global del sistema
* CRUDs de catálogo, reglas, campañas, gastos administrativos, pedidos
* Filament Tables + Forms para tablas avanzadas

Todo completamente integrado con el diseño Alma Mia.

## Temas intercambiables

* Se encuentran en `resources/themes/{nombre_del_tema}`
* Cada tema incluye sus propias páginas Folio
* Los assets se gestionan como archivos estáticos desde `resources/themes/anchor/assets` y se copian a `public/themes/anchor`
* El tema activo se define en `theme.json`. Tus usuarias (vendedoras/líderes) ven el frontend del tema, no el panel administrat
ivo.

### Tema/UI: fondo visual base

* El layout principal del tema `anchor` usa la clase `.fixed-wallpaper` con una imagen local en `asset('storage/bg.jpg')`.
* El archivo físico debe existir en `storage/app/public/bg.jpg` y publicarse en `public/storage/bg.jpg` mediante el symlink de Laravel (`php artisan storage:link`).
* Se debe mantener el overlay actual (`bg-white/50 dark:bg-zinc-950/50`) para preservar legibilidad en modo claro y oscuro.

### `productos`

* SKU, precio, puntos y estados.
* Relación muchos-a-muchos con categorías.

### `categorias`

* Nombre, slug.
* Relación con productos.
* Asociación con reglas de puntaje.

### `puntaje_reglas`

* Define bonificaciones, requisitos y porcentajes.
* Se asocia a categorías mediante tabla pivot.

### `gastos_administrativos`

* Registra POP, envíos, logística.
* Afecta cálculos de campañas.

### `pedidos`

Incluye:

* Totales monetarios.
* Totales de puntos.
* Estados del pedido.
* Flujo de pago por pedido con carga de comprobante (`comprobante_pago_path`) y estado de pago (`sin_pago`, `pendiente_verificacion_lider`, `verificado`, `rechazado`).
* Campos relacionales:

  * `vendedora_id`
  * `lider_id`
* `coordinadora_id`
* `responsable_id`
* JSON `datos_pedido`.

### `pagos` y `cobros`

* `pagos` registra los desembolsos a la **vendedora** de cada pedido, con su monto, estado y el mes de pago programado (por defecto el mes siguiente a la campaña).
* `cobros` conserva los bonos destinados a **líderes** y **coordinadoras**, asociados a la campaña que los origina y opcionalmente al pedido relacionado.
* La lógica completa y ejemplos están documentados en `docs/cobros-y-pagos.md`.

### `pedido_articulos`

* Cantidad.
* Precios.
* Descuentos.
* Puntaje.
* Totales.

### `users`

Extendido desde Wave con:

* `lider_id`
* `coordinadora_id`
* Tabla pivot `coordinadora_lider`.

### Plan de premios y rangos de liderazgo

* Tablas nuevas: `rangos_lideres`, `premio_reglas`, `repartos_compras`, `cierres_campana` y `metricas_lider_campana` para seguir los bonos por líder.
* Relaciones Eloquent con `User` para navegar métricas y rangos por campaña.
* Detalle funcional y montos precargados en `docs/plan-premios-liderazgo.md`.

---

## Temas Intercambiables (Wave Themes)

* Ubicación: `resources/themes/{tema}`
* Cada tema define:

  * Vistas Folio
  * Componentes Volt
  * Estilos y scripts ubicados en `resources/themes/anchor/assets` (CSS y JS planos, sin Vite)
* Configuración mediante `theme.json`

### Gestión de assets sin Vite

El tema activo **no usa Vite**. Los archivos fuente viven en `resources/themes/anchor/assets` y deben publicarse como estáticos pa
ra que el servidor web los entregue directamente:

```bash
mkdir -p public/themes/anchor/{css,js}
cp resources/themes/anchor/assets/css/*.css public/themes/anchor/css/
cp resources/themes/anchor/assets/js/*.js public/themes/anchor/js/
```

Cuando modifiques CSS o JavaScript, repite la copia para reflejar los cambios. No hay proceso de bundling ni hot reload.

---

## Seeders

### `DatabaseSeeder`

Ejecuta todos los seeders principales de Wave.

### `AlmamiaSeeder`

* Carga categorías.
* Reglas de puntaje.
* Tablas pivot.

### `ProductosSeeder`

Importa el catálogo oficial completo. **Advertencia:** borra datos previos.

### Orden recomendado de ejecución de seeders

Para mantener la integridad de los datos de perfil de Wave se sugiere el siguiente flujo después de las migraciones:

1. `php artisan db:seed` (ejecuta `DatabaseSeeder`, que carga `profile_key_values` por defecto).
2. `php artisan db:seed --class=AlmamiaSeeder` (categorías y reglas locales).
3. `php artisan db:seed --class=ProductosSeeder` (catálogo oficial sin afectar `profile_key_values`).

---

## Instalación y configuración

1. **Requisitos previos**: PHP 8.2, Composer y una base de datos MySQL/MariaDB disponible. Node.js solo es necesario si vas a u
sar herramientas opcionales de frontend; el tema se sirve sin Vite.
2. **Clonar el repositorio**:

   ```bash
   git clone <url-del-repo>
   cd alma
   ```

3. **Copiar variables de entorno y generar llave**:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Instalar dependencias PHP**:

   ```bash
   composer install
   ```

5. **(Opcional) Herramientas frontend**: instala dependencias solo si necesitas utilidades Node (por ejemplo, Tailwind CLI o lin
ters).

   ```bash
   npm install
   ```

6. **Ejecutar migraciones y seeders** (ajustar conexión en `.env` si es necesario):

   ```bash
   php artisan migrate --seed
   ```

7. **Publicar assets del tema (sin Vite)**:

   ```bash
   mkdir -p public/themes/anchor/{css,js}
   cp resources/themes/anchor/assets/css/*.css public/themes/anchor/css/
   cp resources/themes/anchor/assets/js/*.js public/themes/anchor/js/
   ```

8. **Levantar el servidor de desarrollo**:

   ```bash
   php artisan serve
   ```

### Distribución de assets estáticos

Ya no existe artefacto de Vite ni paso de build automatizado. Los despliegues solo necesitan que los assets copiados a `public/themes/anchor` estén presentes:

1. Asegúrate de que `public/themes/anchor/css` y `public/themes/anchor/js` contengan los archivos copiados desde `resources/themes/anchor/assets`.
2. Incluye estas carpetas en el proceso de sincronización del entorno (deployment o QA) junto con el resto del código.
3. Si actualizas estilos o scripts, repite la copia antes de publicar.

---

## Testing

```bash
php artisan test
```

---

## Verificación rápida de disponibilidad

Para confirmar que la app responde y no está en mantenimiento se expone el endpoint público:

```
GET /api/health
```

Responde con JSON indicando el estado (`ok` o `mantenimiento`), el entorno activo y si hace falta preparar assets (siempre `false`, porque el monitoreo no depende de builds ni bundlers). Un `503` solo aparecerá cuando se ejecute `php artisan down`.

---

## Comandos Útiles

| Comando                                       | Descripción                     |
| --------------------------------------------- | ------------------------------- |
| `php artisan migrate:fresh --seed`            | Reset total de la base de datos |
| `php artisan db:seed --class=AlmamiaSeeder`   | Carga categorías y reglas       |
| `php artisan db:seed --class=ProductosSeeder` | Importa catálogo completo       |
| `php artisan make:filament-resource`          | Crea un CRUD en Filament        |
| `php artisan folio:page`                      | Genera nuevas páginas Folio     |

---

## Navegación del Código

| Carpeta               | Contenido                   |
| --------------------- | --------------------------- |
| `app/Models`          | Modelos personalizados      |
| `resources/themes`    | Frontend público            |
| `wave/`               | Framework base Wave         |
| `database/migrations` | Estructura de base de datos |
| `tests/`              | Pruebas unitarias           |

---

## Información Oficial de Wave (en español)

Wave es un framework SaaS basado en Laravel que permite crear aplicaciones rápidas y completas. Provee un sistema de autenticación, perfiles, impersonación, planes, facturación, roles y permisos, notificaciones, changelog, blog, páginas, API, panel administrativo, temas y plugins.

Para más información: [https://devdojo.com/wave/docs](https://devdojo.com/wave/docs)

---

## Demo de Wave

Puedes ver la demo en vivo y los temas disponibles aquí: [https://devdojo.com/wave/demo](https://devdojo.com/wave/demo)

---

## Instalación de Wave

Wave puede instalarse mediante instalador automático o manual. Instrucciones completas en: [https://devdojo.com/wave/docs/install](https://devdojo.com/wave/docs/install)

---

## Apoyo al Proyecto Wave

Puedes apoyar a DevDojo utilizando una cuenta Pro, que desbloquea contenido premium, videos y soporte, y ayuda al desarrollo continuo del framework.
