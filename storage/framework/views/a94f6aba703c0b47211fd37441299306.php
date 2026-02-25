
<script>
    function pedidoApp(){
        return {
            search: '',
            filter_categoria: '',
            categorias: <?php echo json_encode(collect($categorias ?? [])->pluck('nombre'), 15, 512) ?>,
            productos: <?php echo json_encode($productos ?? [], 15, 512) ?>,
            paginas: <?php echo json_encode($paginas ?? [], 15, 512) ?>,
            paginaActiva: 0,
            vendedorasPerfil: <?php echo json_encode($vendedoras ?? [], 15, 512) ?>,
            lideresPerfil: <?php echo json_encode($lideres ?? [], 15, 512) ?>,
            responsablePerfil: <?php echo json_encode($responsable ?? [], 15, 512) ?>,
            esVendedoraAutenticada: <?php echo json_encode($esVendedoraAutenticada ?? false, 15, 512) ?>,
            esLiderAutenticado: <?php echo json_encode($esLiderAutenticado ?? false, 15, 512) ?>,
            cart: [],
            gastosDisponibles: <?php echo json_encode($gastos ?? [], 15, 512) ?>,
            gastosSeleccionados: [],
            vendedoraPrefijadaId: <?php echo json_encode($vendedoraSeleccionadaId ?? null, 15, 512) ?>,
            liderPrefijadaId: <?php echo json_encode($liderSeleccionadoId ?? null, 15, 512) ?>,
            vendedora_id: <?php echo json_encode($vendedoraSeleccionadaId ?? null, 15, 512) ?>,
            lider_id: <?php echo json_encode($liderSeleccionadoId ?? null, 15, 512) ?>,
            totalGastos: 0,
            observaciones: '',
            codigo_pedido: <?php echo json_encode($codigoPedido ?? null, 15, 512) ?>,
            messages: {success:null,error:null},
            isSubmitting: false,
            subtotal: 0,
            totalPuntos: 0,
            totalUnidades: 0,
            totalGanancias: 0,
            openCategorias: {},
            estadoBotonCatalogo: {},
            temporizadoresBotonCatalogo: {},
            datosPedido: {
                lider:       { nombre: null, direccion: null, zona: null },
                vendedora:   { nombre: null, direccion: null, zona: null },
                responsable: { nombre: null, direccion: null, zona: null },
            },
            successModalOpen: false,
            successModalMessage: '',

            init(){
                this.datosPedido.responsable = {
                    nombre: this.responsablePerfil?.name ?? null,
                    direccion: this.responsablePerfil?.direccion ?? null,
                    zona: this.responsablePerfil?.zona ?? null,
                };
                this.gastosSeleccionados = this.gastosDisponibles.map(g => g.id);
                this.categorias.forEach(cat => {
                    this.openCategorias[cat] = true;
                });
                this.updateDatosPedido();
                this.calculateTotalGastos();
                this.$watch('vendedora_id', () => this.updateDatosPedido());
                this.$watch('lider_id', () => this.updateDatosPedido());
            },

            formatMoney(val){
                const numero = Number(val ?? 0);
                return numero.toLocaleString('es-AR',{minimumFractionDigits:2, maximumFractionDigits:2});
            },

            // CORRECCIÓN: Búsqueda robusta por Nombre y SKU
            // Búsqueda para el filtrado visual
           matchesSearch(prod) {
    let s = (this.search || '').toLowerCase().trim();
    if (!s) return true;

    const nombre = (prod.nombre || '').toLowerCase();
    // Importante: Convertir SKU e ID a String para no perder los ceros a la izquierda
    const sku = String(prod.sku || '').toLowerCase().trim();
    const id = String(prod.id || '').toLowerCase().trim();

    // Retorna verdadero si el término está en el nombre o es IGUAL al SKU
    return nombre.includes(s) || sku.includes(s) || id === s;
}, // <--- REVISA QUE ESTA COMA ESTÉ AQUÍ




            // Función para agregar automáticamente por SKU
            addBySku() {
    // 1. Limpiamos el término buscado
    let term = (this.search || '').trim().toLowerCase();
    if (!term) return;

    // 2. Buscamos en 'this.productos' (que ya tiene los datos de tu tabla 'productos')
    const productoEncontrado = this.productos.find(p => {
        // Forzamos a string y limpiamos espacios para evitar errores con ceros a la izquierda
        let skuDb = String(p.sku || '').trim().toLowerCase();
        let idDb = String(p.id || '').trim().toLowerCase();
        
        return skuDb === term || idDb === term;
    });

    // 3. Lógica de agregado
    if (productoEncontrado) {
        // Verificamos si no está bloqueado por reglas de visibilidad
        if (this.esProductoVisibilidadBloqueado(productoEncontrado)) {
            this.messages.error = "Este producto requiere un mínimo de unidades en el pedido.";
        } else {
            this.addToCart(productoEncontrado);
            this.search = ''; // Limpiamos el buscador
            this.messages.error = null;
        }
    } else {
        this.messages.error = "No se encontró el SKU: " + term;
        setTimeout(() => { this.messages.error = null; }, 3000);
    }
}, // <--- REVISA QUE ESTA COMA ESTÉ AQUÍ

            getReglasProducto(prod){
                return Array.isArray(prod.reglas) ? prod.reglas : [];
            },

            getReglaVisibilidad(prod){
                return this.getReglasProducto(prod)
                    .find(r => (r.descripcion || '').toUpperCase() === 'REGLAVISIBILIDAD');
            },

            getReglasDescuento(prod){
                return this.getReglasProducto(prod)
                    .filter(r => (r.descripcion || '').toUpperCase() === 'REGLADESCUENTO');
            },

            getPorcentajeDescuentoParaProducto(prod, totalUnidadesPedido){
                const reglas = this.getReglasDescuento(prod);
                let match = reglas.find(r => {
                    const min = Number(r.min_unidades ?? 0);
                    const max = (r.max_unidades === null || r.max_unidades === undefined) ? Infinity : Number(r.max_unidades);
                    return totalUnidadesPedido >= min && totalUnidadesPedido <= max;
                });
                return match ? Number(match.porcentaje ?? 0) : 0;
            },

            esProductoVisibilidad(prod){
                return !!this.getReglaVisibilidad(prod);
            },

            esProductoVisibilidadBloqueado(prod){
                const regla = this.getReglaVisibilidad(prod);
                if(!regla) return false;
                const min = Number(regla.puntaje_minimo ?? 0);
                const totalUnidadesPedido = this.cart.reduce((a,b) => a + Number(b.cantidad ?? 0), 0);
                return totalUnidadesPedido < min;
            },

            getProductoById(id){
                return this.productos.find(p => Number(p.id) === Number(id));
            },

            notifyAddedToCart(){
                try {
                    if (typeof FilamentNotification !== 'undefined') {
                        new FilamentNotification().title('Agregado al carrito').send();
                    }
                } catch (e) {}
            },
            
            activarAnimacionAgregado(productoId){
                if(!productoId) return;
                const id = Number(productoId);
                if (this.temporizadoresBotonCatalogo[id]) {
                    clearTimeout(this.temporizadoresBotonCatalogo[id]);
                }
                this.estadoBotonCatalogo = { ...this.estadoBotonCatalogo, [id]: 'agregado' };
                this.temporizadoresBotonCatalogo[id] = setTimeout(() => {
                    this.estadoBotonCatalogo = { ...this.estadoBotonCatalogo, [id]: null };
                }, 1200);
            },
            
            goToMisPedidos(){
                window.location.href = '/mis-pedidos';
            },

            addToCart(prod){
                const reglaVis = this.getReglaVisibilidad(prod);
                if (reglaVis) {
                    const min = Number(reglaVis.puntaje_minimo ?? 0);
                    const totalUnidadesPedido = this.cart.reduce((a,b) => a + Number(b.cantidad ?? 0), 0);
                    if (totalUnidadesPedido < min) {
                        this.messages.error = `Para agregar este producto necesitás al menos ${min} unidades en el pedido.`;
                        return;
                    }
                }

                let item = this.cart.find(i => i.producto_id === prod.id);
                if(item){
                    item.cantidad++;
                }else{
                    const precioUnitario = Number(prod.precio);
                    this.cart.push({
                        producto_id: prod.id,
                        nombre: prod.nombre,
                        cantidad: 1,
                        precio_unitario_catalogo: precioUnitario,
                        porcentaje_descuento: 0,
                        precio_con_descuento: precioUnitario,
                        subtotal: precioUnitario,
                        puntos: prod.puntos_por_unidad
                    });
                }
                this.messages.error = null;
                this.calculateTotals();
                this.notifyAddedToCart();
                this.activarAnimacionAgregado(prod.id);
            },

            increaseQty(index){
                this.cart[index].cantidad++;
                this.calculateTotals();
            },

            decreaseQty(index){
                if(this.cart[index].cantidad > 1){
                    this.cart[index].cantidad--;
                    this.calculateTotals();
                }
            },

            removeItem(index){
                this.cart.splice(index,1);
                this.calculateTotals();
            },

            updateItem(index){
                let item = this.cart[index];
                if(item.cantidad < 1) item.cantidad = 1;
                this.calculateTotals();
            },

            clearCart(){
                this.cart = [];
                this.calculateTotals();
            },

            recalculateCartPrices(totalUnidadesPedido){
                this.cart.forEach(item => {
                    const prod = this.productos.find(p => p.id === item.producto_id);
                    if(!prod) return;
                    const reglaVis = this.getReglaVisibilidad(prod);
                    let porcentaje = 0;
                    if (reglaVis) {
                        porcentaje = Number(reglaVis.porcentaje ?? 0);
                    } else {
                        porcentaje = this.getPorcentajeDescuentoParaProducto(prod, totalUnidadesPedido);
                    }
                    const precioUnit = Number(item.precio_unitario_catalogo ?? 0);
                    const precioDesc = Number((precioUnit * (1 - (porcentaje/100))).toFixed(2));
                    item.porcentaje_descuento = porcentaje;
                    item.precio_con_descuento = precioDesc;
                    item.subtotal = precioDesc * Number(item.cantidad ?? 0);
                });
            },

            calculateTotals(){
                let totalUnidadesPedido = this.cart.reduce((a,b) => a + Number(b.cantidad ?? 0), 0);
                this.cart = this.cart.filter(item => {
                    const prod = this.productos.find(p => p.id === item.producto_id);
                    if(!prod) return true;
                    const reglaVis = this.getReglaVisibilidad(prod);
                    if(!reglaVis) return true;
                    const min = Number(reglaVis.puntaje_minimo ?? 0);
                    const unidadesSinEste = totalUnidadesPedido - Number(item.cantidad ?? 0);
                    return unidadesSinEste >= min;
                });
                totalUnidadesPedido = this.cart.reduce((a,b) => a + Number(b.cantidad ?? 0), 0);
                this.recalculateCartPrices(totalUnidadesPedido);
                this.subtotal = this.cart.reduce((a,b) => a + Number(b.subtotal ?? 0), 0);
                this.totalPuntos = this.cart.reduce((a,b) => a + Number(b.puntos ?? 0) * Number(b.cantidad ?? 0), 0);
                this.totalUnidades = totalUnidadesPedido;
                this.totalGanancias = this.cart.reduce((a,b) => {
                    const precioUnit = Number(b.precio_unitario_catalogo ?? 0);
                    const precioDesc = Number(b.precio_con_descuento ?? precioUnit);
                    return a + (precioUnit - precioDesc) * Number(b.cantidad ?? 0);
                }, 0);
                this.calculateTotalGastos();
            },

            calculateTotalGastos(){
                this.totalGastos = this.gastosSeleccionados.reduce((total, gastoId) => {
                    const encontrado = this.gastosDisponibles.find(g => Number(g.id) === Number(gastoId));
                    return encontrado ? total + Number(encontrado.monto ?? 0) : total;
                }, 0);
            },

            updateDatosPedido(){
                let vendedora = this.vendedorasPerfil.find(v => String(v.id) === String(this.vendedora_id));
                this.datosPedido.vendedora = {
                    nombre: vendedora?.name ?? null,
                    direccion: vendedora?.direccion ?? null,
                    zona: vendedora?.zona ?? null,
                };
                let lider = this.lideresPerfil.find(l => String(l.id) === String(this.lider_id));
                this.datosPedido.lider = {
                    nombre: lider?.name ?? null,
                    direccion: lider?.direccion ?? null,
                    zona: lider?.zona ?? null,
                };
            },

            async storePedidoLogic(){
                let res = await this.$wire.storePedido(
                    this.cart.map(i => ({
                        producto_id: i.producto_id,
                        nombre: i.nombre,
                        cantidad: i.cantidad,
                        precio_unitario: i.precio_unitario_catalogo,
                        porcentaje_descuento: i.porcentaje_descuento,
                        precio_unitario_descuento: i.precio_con_descuento,
                        subtotal: i.subtotal,
                        puntos: i.puntos,
                    })),
                    this.vendedora_id,
                    this.lider_id,
                    this.gastosSeleccionados,
                    this.observaciones
                );
                if(res.success){
                    this.messages.success = res.success;
                    this.cart = [];
                    this.calculateTotals();
                    this.observaciones = '';
                    this.codigo_pedido = res.codigo_pedido ?? this.codigo_pedido;
                    this.successModalMessage = res.success;
                    this.successModalOpen = true;
                    setTimeout(() => { this.goToMisPedidos(); }, 1800);
                } else {
                    this.messages.error = res.error;
                }
            },

            handleSubmit(){
                if (this.isSubmitting) return;
                this.isSubmitting = true;
                this.storePedidoLogic().finally(() => {
                    if (!this.successModalOpen) this.isSubmitting = false;
                });
            }
        }
    }
</script>
<?php /**PATH /home/unquxtyh/public_html/resources/themes/anchor/pages/partials/pedido-app-script.blade.php ENDPATH**/ ?>