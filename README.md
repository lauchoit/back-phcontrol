# phcontrol

Backend para el control de inventario de PokeHouse.

phcontrol nace para llevar el inventario semanal de productos, registrar las entregas de proveedores, controlar los envios de mercancia entre sedes, medir el consumo de la sede principal de Brickell y generar reportes en PDF con las cantidades inventariadas.

El sistema esta construido con Laravel siguiendo una arquitectura hexagonal por modulo. La intencion es mantener las reglas de negocio separadas del framework y dejar Laravel concentrado en la capa de infraestructura: controladores, modelos Eloquent, requests, resources, policies, rutas y persistencia.

## Objetivo del sistema

PokeHouse necesita saber, semana a semana, cuanto producto queda disponible, cuanto entra por proveedores, cuanto se envia a otras sedes y cuanto consume Brickell como sede principal.

El objetivo de phcontrol es centralizar ese flujo para que el inventario no dependa de hojas sueltas, mensajes o calculos manuales. Cada movimiento debe quedar registrado, trazable y disponible para consulta o reporte.

## Flujo principal

1. Se crea o selecciona un periodo semanal de inventario.
2. Se registra la existencia inicial o el conteo actual de cada producto.
3. Se registran las cantidades entregadas por los proveedores durante la semana.
4. Se registran las cantidades enviadas desde Brickell hacia otras sedes.
5. Se registra o calcula el consumo de Brickell.
6. Se calcula cuanto queda disponible por producto.
7. Se genera un PDF del inventario semanal con las cantidades de cada producto.

## Conceptos del negocio

### Productos

Representan los insumos o articulos que PokeHouse necesita controlar. Cada producto debe poder estar activo o inactivo y debe conservar un orden para facilitar la visualizacion en listas, formularios y reportes.

El modulo `Product` ya existe y actualmente incluye CRUD protegido por autenticacion y autorizacion.

### Inventarios semanales

Cada semana debe tener un inventario asociado. Ese inventario representa el estado de los productos dentro de un periodo especifico.

El inventario semanal debe permitir responder preguntas como:

- Cuanto quedo de cada producto al cierre de la semana.
- Cuanto producto entro por proveedores.
- Cuanto producto salio hacia otras sedes.
- Cuanto consumio Brickell.
- Que diferencias existen entre lo esperado y lo contado.

### Proveedores

Los proveedores entregan cantidades de productos semanalmente. El sistema debe registrar esas entregas por producto, proveedor, fecha y cantidad.

Esto permite saber cuanto se compro o recibio realmente durante cada semana y comparar esas entradas contra el consumo y los envios.

### Sedes

Brickell es la sede principal. Desde ahi se controla el inventario central y se registran envios de productos a otras sedes.

Las otras sedes deben poder recibir cantidades de productos, quedando cada envio registrado como movimiento de inventario.

### Consumo de Brickell

El consumo de Brickell representa la cantidad de producto utilizada por la sede principal durante la semana.

Ese consumo puede calcularse a partir de:

- Inventario inicial.
- Entradas de proveedores.
- Salidas hacia otras sedes.
- Inventario final.

Formula conceptual:

```text
consumo_brickell = inventario_inicial + entradas_proveedores - envios_otras_sedes - inventario_final
```

La implementacion final puede ajustar esta formula si aparecen movimientos adicionales, devoluciones, mermas o correcciones.

### Reportes PDF

El sistema debe generar PDFs de los inventarios creados. Los reportes deben incluir las cantidades de cada producto y servir como respaldo operativo para revision, cierre semanal o envio administrativo.

Un PDF de inventario deberia incluir, como minimo:

- Semana o rango de fechas.
- Fecha de generacion.
- Productos inventariados.
- Cantidad inicial.
- Cantidad recibida por proveedores.
- Cantidad enviada a otras sedes.
- Consumo de Brickell.
- Cantidad final.
- Usuario que genero el reporte.

## Arquitectura

El proyecto sigue una estructura modular con arquitectura hexagonal:

```text
src/
  Product/
    Domain/
    Application/
    Infrastructure/
    Tests/
  User/
    Domain/
    Application/
    Infrastructure/
    Tests/
  Auth/
    Domain/
    Application/
    Infrastructure/
    Tests/
  AccessControl/
    Domain/
    Application/
    Infrastructure/
    Tests/
```

Cada modulo mantiene separadas sus responsabilidades:

- `Domain`: entidades, contratos, excepciones y reglas propias del negocio.
- `Application`: casos de uso que orquestan las operaciones del modulo.
- `Infrastructure`: adaptadores Laravel, controladores, requests, resources, modelos, policies, rutas y repositorios concretos.
- `Tests`: pruebas unitarias y feature tests del modulo.

La regla principal es que `Domain` y `Application` no deben depender de Laravel. La dependencia con el framework debe vivir en `Infrastructure`.

## Modulos actuales

### Auth

Gestiona autenticacion y generacion de tokens para consumir la API.

### User

Gestiona usuarios del sistema.

### AccessControl

Gestiona roles, permisos y autorizacion. Usa permisos para proteger acciones del API.

### Product

Gestiona el catalogo de productos del inventario. El CRUD esta protegido por autenticacion y policies.

Permisos principales:

- `product.find.all`
- `product.find.by.id`
- `product.create`
- `product.update.by.id`
- `product.delete.by.id`

## Modulos planeados

### Inventory

Modulo encargado de crear y cerrar inventarios semanales.

Responsabilidades esperadas:

- Crear inventario semanal.
- Asociar productos al inventario.
- Registrar conteos iniciales y finales.
- Calcular cantidades disponibles.
- Marcar inventarios como abiertos, cerrados o anulados.

### Supplier

Modulo para administrar proveedores.

Responsabilidades esperadas:

- Crear proveedores.
- Activar o desactivar proveedores.
- Asociar entregas de productos a un proveedor.

### SupplierDelivery

Modulo para registrar las cantidades que entregan los proveedores semanalmente.

Responsabilidades esperadas:

- Registrar fecha de entrega.
- Registrar proveedor.
- Registrar productos y cantidades entregadas.
- Asociar la entrega a una semana o inventario.

### Branch

Modulo para administrar sedes.

Responsabilidades esperadas:

- Registrar sedes de PokeHouse.
- Identificar Brickell como sede principal.
- Activar o desactivar sedes.

### StockTransfer

Modulo para registrar envios de productos desde Brickell hacia otras sedes.

Responsabilidades esperadas:

- Registrar sede destino.
- Registrar productos enviados.
- Registrar cantidades enviadas.
- Asociar el envio al inventario semanal correspondiente.

### BrickellConsumption

Modulo para registrar o calcular el consumo de Brickell.

Responsabilidades esperadas:

- Calcular consumo por producto.
- Permitir ajustes controlados si hace falta.
- Mantener trazabilidad de cambios.

### InventoryReport

Modulo para generar reportes PDF de inventario.

Responsabilidades esperadas:

- Generar PDF por inventario semanal.
- Mostrar resumen por producto.
- Incluir entradas, salidas, consumo y cantidad final.
- Guardar o entregar el archivo generado.

## Seguridad

La API debe estar protegida por autenticacion y autorizacion.

Patron esperado para los endpoints:

1. Usuario no autenticado: respuesta `401`.
2. Usuario autenticado sin permiso: respuesta `403`.
3. Usuario autorizado: respuesta exitosa.
4. Validaciones propias del endpoint.

Los tests de cada CRUD deben seguir ese mismo orden para que la intencion quede clara y consistente.

## Testing

El proyecto usa PHPUnit.

Comandos utiles:

```bash
php artisan test --compact
```

Para correr solo un modulo:

```bash
php artisan test --compact src/Product/Tests
```

Cada modulo debe tener pruebas unitarias para dominio y pruebas feature para endpoints.

## Formato de codigo

Despues de modificar archivos PHP se debe correr Pint:

```bash
vendor/bin/pint --dirty --format agent
```

## Estado actual

El sistema ya cuenta con la base de:

- Autenticacion.
- Usuarios.
- Roles y permisos.
- Catalogo de productos protegido.
- Estructura hexagonal por modulo.
- Pruebas automatizadas para los modulos existentes.

El siguiente paso natural es construir el flujo de inventario semanal alrededor de los productos, proveedores, sedes, movimientos y reportes PDF.
