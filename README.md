# 🐾 Sistema de Veterinaria (PHP + MySQL)

Sistema MVC en PHP con MySQL. Incluye mascotas, tratamientos, historial clínico (se crea solo al registrar una mascota),
servicios, métodos de pago (con **QR de cobro** según el monto) y usuarios Veterinario / Secretaria.

Funciona en **local (XAMPP)** y en **Render** (con Docker). El código lee la conexión de variables de entorno
y, si no existen, usa los valores de XAMPP (`localhost`, `root`, sin clave).

---

## 🚀 Subir a Render (paso a paso)

Render no ejecuta PHP de forma nativa (usa el `Dockerfile` incluido) y tampoco ofrece MySQL, así que la base de datos va en un
MySQL externo. La opción gratuita recomendada es **TiDB Cloud Starter** (compatible con MySQL, sin tarjeta).

### 1) Crear la base de datos en TiDB Cloud
1. Crea una cuenta en <https://tidbcloud.com> → **Create Resource** → plan **Starter** → modo **MySQL Compatible**.
2. Entra a la instancia → botón **Connect** → endpoint **Public** → **Generate Password** (guárdala).
3. Anota estos datos: **Host**, **Port** (4000), **User** (tiene un prefijo, ej. `abc123.root`), **Password** y **Database** (por defecto `test`).

> No hace falta importar nada a mano: al arrancar, el sistema crea las tablas y los datos iniciales solo si la base está vacía
> (y nunca borra datos al redesplegar).

### 2) Subir el proyecto a GitHub
1. Crea un repositorio nuevo en GitHub.
2. Sube **el contenido de esta carpeta** (el `Dockerfile` debe quedar en la raíz del repositorio, no dentro de otra carpeta).

```bash
git init
git add .
git commit -m "Sistema de veterinaria"
git branch -M main
git remote add origin https://github.com/TU_USUARIO/TU_REPO.git
git push -u origin main
```

### 3) Crear el servicio en Render
1. <https://render.com> → **New +** → **Web Service** → conecta tu repositorio.
2. **Language / Runtime:** `Docker` · **Instance Type:** `Free` (o superior).
3. **Advanced → Health Check Path:** `/health.php`
4. Agrega estas **Environment Variables**:

| Variable | Valor |
|---|---|
| `DB_HOST` | el Host de TiDB |
| `DB_PORT` | `4000` |
| `DB_NAME` | `test` (o el nombre de tu base) |
| `DB_USER` | el User de TiDB (con prefijo) |
| `DB_PASS` | la contraseña generada |
| `DB_SSL` | `1` |
| `ADMIN_CLAVE` | *(recomendado)* contraseña nueva del usuario `admin` |
| `SECRETARIA_CLAVE` | *(recomendado)* contraseña nueva del usuario `secretaria` |

5. **Create Web Service**. Cuando termine el deploy, abre la URL que te da Render.

También puedes usar el archivo `render.yaml` (Blueprint): Render lo detecta y precarga estas variables; solo completas las que están en blanco.

### 4) Primer ingreso
- Veterinario: usuario `admin` (pestaña *Veterinario*)
- Secretaria: usuario `secretaria` (pestaña *Secretaria*)
- Contraseña: la que pusiste en `ADMIN_CLAVE` / `SECRETARIA_CLAVE`. Si no las definiste: `admin123` y `secre123`.
  **Como el repositorio es público, cámbialas.** `ADMIN_CLAVE` y `SECRETARIA_CLAVE` solo se aplican en la primera inicialización
  de una base vacía; después, cambia la clave del admin desde la base de datos o borra las tablas para reinicializar.

---

## ⚠️ Cosas a tener en cuenta en Render gratis
- El servicio **se duerme tras 15 min sin visitas** y tarda hasta ~1 minuto en despertar.
- Al dormirse o redesplegar se cierran las sesiones: hay que volver a iniciar sesión (los datos están a salvo en la base).
- Si la base de datos (TiDB) está inactiva, la primera carga puede tardar unos segundos más.

## 🔧 Variables de conexión (referencia)
| Variable | Por defecto | Para qué sirve |
|---|---|---|
| `DB_HOST` | `localhost` | servidor MySQL |
| `DB_PORT` | `3306` | puerto |
| `DB_NAME` | `veterinaria_db` | nombre de la base |
| `DB_USER` / `DB_PASS` | `root` / *(vacía)* | credenciales |
| `DB_SSL` | *(sin definir)* | `1` = conexión cifrada (necesaria en TiDB Cloud) |
| `DB_SSL_VERIFY` | verifica | `0` = no verificar el certificado (solo si tu proveedor lo exige) |
| `DB_SSL_CA` | CA del sistema | ruta a un certificado CA propio |

## 💻 Uso local con XAMPP (sin Docker)
1. Copia la carpeta en `htdocs` e importa `database.sql` en phpMyAdmin.
2. Abre `http://localhost/VeterinariaSistema/` (usuarios: `admin / admin123`, `secretaria / secre123`).
3. Si ya tenías una base anterior: importa `migracion.sql` en lugar de `database.sql`.

## 📁 Estructura
```
config/  controllers/  models/  views/  assets/   ← aplicación (MVC)
database.sql   migracion.sql                      ← base de datos
Dockerfile  docker/  render.yaml  health.php      ← despliegue en Render
```
