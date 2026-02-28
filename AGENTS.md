# AGENTS.md

# Guía para Colaboradores y Asistentes Internos

Esta guía establece las reglas de contribución, estilo y funcionamiento interno del proyecto **Alma Mia Fragancias**, incluyendo lineamientos para desarrolladores, asistentes de IA internos, agentes automáticos y herramientas que interactúan con el repositorio.

Su objetivo es garantizar un flujo de trabajo ordenado, consistente, confiable y escalable.

---

# 📌 1. Documentación SIEMPRE en español

Toda documentación visible para el equipo o para usuarias finales debe escribirse en **español neutro**, con estilo claro y profesional.

Esto incluye:

* README.md
* AGENTS.md
* Comentarios en código
* Copys en vistas públicas
* Textos de panel administrativo
* Documentación de módulos
* Descripciones de PR

**Evitar anglicismos** salvo cuando sean inevitables por terminología técnica.

Ejemplo: usar “pedido” en lugar de “order”, “rol” en lugar de “role”.

---

# 📌 2. Sincronización entre datos, código y documentación

Cuando se realicen cambios relacionados con:

* productos
* categorías
* reglas de puntaje
* jerarquías
* pedidos
* campañas
* puntos o bonificaciones

El colaborador debe:

### ✔️ 2.1 Crear migración correspondiente

Toda modificación estructural debe estar respaldada por migraciones MySQL.

### ✔️ 2.2 Actualizar modelos en `app/Models`

Esto incluye:

* nuevos fillables
* nuevas relaciones
* casts
* eventos de modelo necesarios

### ✔️ 2.3 Actualizar seeders oficiales

* `AlmamiaSeeder`
* `ProductosSeeder`

### ✔️ 2.4 Registrar cambios en la documentación

Si altera el negocio, debe quedar reflejado en:

* README.md
* un archivo docs/NOMBRE-MODULO.md (si aplica)
* notas de versión internas

---

# 📌 3. Cambios de lógica comercial

Cualquier funcionalidad que impacte en:

* estructura Vendedora → Líder → Coordinadora
* cálculos de puntos
* bonificaciones
* requisitos de campañas
* estados del pedido
* proceso de onboarding
* cálculo de costos
* asignaciones automáticas

Debe estar documentada obligatoriamente con:

* explicación funcional
* ejemplo práctico
* impacto en BD y modelos

**Nunca merges sin documentación adjunta si afecta lógica comercial.**

---

# 📌 4. Dependencias y mantenimiento

Este proyecto utiliza Wave como base, por lo tanto se gestionan múltiples paquetes críticos:

* Filament
* Folio
* Livewire 3
* Volt
* Spatie Roles & Permissions
* JWT Auth
* Stripe
* Impersonate
* Tailwind Merge Laravel
* Blade Phosphor Icons

Si un colaborador agrega o modifica una dependencia fundamental:

### ✔️ debe actualizar el apartado "Dependencias" en el README

### ❗ NO debe editar composer.json


### ✔️ documentar dependencias frontend

* Toda librería añadida o actualizada en `package.json` (Tailwind, Vite, Alpine, Axios, plugins, etc.) debe reflejarse en el apartado "Dependencias" del README.
* Si el cambio es significativo, registrar también la nota correspondiente en la documentación interna.


> Esto garantiza trazabilidad automática y evita inconsistencias.

---

# 📌 5. Pruebas automatizadas obligatorias

Toda lógica crítica debe estar testeada.

Requiere pruebas en `tests/` cuando se modifica:

* cálculo de puntos o bonificaciones
* jerarquías comerciales
* estados del pedido
* totales monetarios
* migraciones relevantes
* carga de seeders

Comando obligatorio antes de cada PR:

```
php artisan test
```

Si falla alguna prueba, la PR no debe aprobarse.

---

# 📌 6. No modificar `/wave/` a menos que sea estrictamente necesario, en lo posible evitar

El directorio `wave/` contiene el código fuente del framework DevDojo Wave.

### ❗ Regla estricta:

**NO** modificar este código a menos que se esté actualizando Wave a una nueva versión o sea estrictamente necesario.

Para personalizaciones utilizar:

* `app/`
* `resources/themes/`
* extensiones propias
* sobrescritura con providers
* 


# 6.1 NO MODIFICAR ROUTES (Utilizar siempre folio y volt)

---

# 📌 7. Lineamientos para asistentes de IA y agentes

Este repositorio puede usar agentes automáticos o asistentes internos (GPT / IA) para documentar y generar código. Deben seguir estas reglas:

### ✔️ 7.1 Escribir SIEMPRE en español

### ✔️ 7.2 Nunca inventar funcionalidades no solicitadas

### ✔️ 7.3 Toda sugerencia debe ser coherente con la arquitectura del proyecto

### ✔️ 7.4 Si se sugiere un cambio de negocio → debe pedirse confirmación

### ✔️ 7.5 Las respuestas deben incluir contexto del proyecto Alma Mia

### ✔️ 7.6 No editar composer.json manualmente

### ✔️ 7.7 No crear estructuras Wave desde cero; respetar su arquitectura

### ✔️ 7.8 Seguir la misma estructura de código que el equipo humano

Si un asistente genera migraciones, modelos o documentación, deben cumplir los estándares definidos en este archivo.

---

# 📌 8. Buenas prácticas de commits

Usar formato claro:

```
feat: agrega reglas de puntaje para campaña 2025
fix: corrige cálculo de puntos por categoría
docs: actualiza README con nueva estructura de pedidos
refactor: mejora relaciones entre coordinadoras y líderes
```


# 📌 9. Lineamiento visual base del tema

Para mantener consistencia visual en pantallas actuales y futuras del tema `anchor`:

* El fondo global debe usar la imagen local `asset('storage/bg.jpg')` desde `.fixed-wallpaper`.
* El archivo fuente debe estar en `storage/app/public/bg.jpg` y accesible públicamente en `public/storage/bg.jpg` mediante `php artisan storage:link`.
* Se debe conservar la superposición (overlay) `bg-white/50 dark:bg-zinc-950/50` para garantizar legibilidad en modo claro/oscuro.

---

