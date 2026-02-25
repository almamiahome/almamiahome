{{-- Script compartido para /crearpedido y /catalogo. Mantiene una única API Alpine/JS para ambas pantallas. --}}
<script>
    function pedidoApp(){
        return {
            // ==== ESTADO INICIAL ====
            search: '',
            filter_categoria: '',
            categorias: @json(collect($categorias ?? [])->pluck('nombre')),
            productos: @json($productos ?? []),
            paginas: @json($paginas ?? []),
            paginaActiva: 0,
            vendedorasPerfil: @json($vendedoras ?? []),
            lideresPerfil: @json($lideres ?? []),
            responsablePerfil: @json($responsable ?? []),
            esVendedoraAutenticada: @json($esVendedoraAutenticada ?? false),
            esLiderAutenticado: @json($esLiderAutenticado ?? false),
            cart: [],
            gastosDisponibles: @json($gastos ?? []),
            gastosSeleccionados: [],
            vendedora_id: @json($vendedoraSeleccionadaId ?? null),
            lider_id: @json($liderSeleccionadoId ?? null),
            totalGastos: 0,
            observaciones: '',
            codigo_pedido: @json($codigoPedido ?? null),
            messages: {success:null, error:null},
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

            // ==== INICIALIZACIÓN ====
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

            // ==== HELPERS FORMATO ====
            formatMoney(val){
                const numero = Number(val ?? 0);
                return numero.toLocaleString('es-AR',{minimumFractionDigits:2, maximumFractionDigits:2});
            },

            // ==== LÓGICA DE BÚSQUEDA Y SKU (Consolidada) ====
            matchesSearch(prod) {
                let s = (this.search || '').toLowerCase().trim();
                if (!s) return true;

                const nombre = (prod.nombre || '').toLowerCase();
                const sku = String(prod.sku || '').toLowerCase().trim();
                const id = String(prod.id || '').toLowerCase().trim();

                // Coincidencia por nombre (parcial) o SKU/ID (exacto o parcial)
                return nombre.includes(s) || sku.includes(s) || id === s;
            },

            addBySku() {
                let term = (this.search || '').trim().toLowerCase();
                if (!term) return;

                const productoEncontrado = this.productos.find(p => {
                    let skuDb = String(p.sku || '').trim().toLowerCase();
                    let idDb = String(p.id || '').trim().toLowerCase();
                    return skuDb === term || idDb === term;
                });

                if (productoEncontrado) {
                    if (this.esProductoVisibilidadBloqueado(productoEncontrado)) {
                        this.messages.error = "Este producto requiere un mínimo de unidades en el pedido.";
                    } else {
                        this.addToCart(productoEncontrado);
                        this.search = ''; 
                        this.messages.error = null;
                    }
                } else {
                    this.messages.error = "No se encontró el SKU: " + term;
                    setTimeout(() => { this.messages.error = null; }, 3000);
                }
            },

            // ==== REGLAS DE NEGOCIO ====
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

            // ==== CATÁLOGO VISUAL ====
            irAPagina(index){
                if(index < 0 || index >= this.paginas.length) return;
                this.paginaActiva = index;
            },

            paginaAnterior(){
                if(this.paginas.length === 0) return;
                this.paginaActiva = (this.paginas.length + this.paginaActiva - 1) % this.paginas.length;
            },

            paginaSiguiente(){
                if(this.paginas.length === 0) return;
                this.paginaActiva = (this.paginas.length + this.paginaActiva + 1) % this.paginas.length;
            },

            posicionFlotante(producto){
                const clamp = (valor) => {
                    const numero = Number(valor);
                    return Number.isNaN(numero) ? 50 : Math.min(100, Math.max(0, numero));
                };
                return `left:${clamp(producto?.pos_x)}%; top:${clamp(producto?.pos_y)}%; transform: translate(-50%, -50%);`;
            },

            getProductoById(id){
                return this.productos.find(p => Number(p.id) === Number(id));
            },

            addProductoDesdePagina(productoCatalogo){
                const productoId = productoCatalogo?.producto_id ?? productoCatalogo?.id;
                const prod = (productoId ? this.getProductoById(productoId) : null) ?? productoCatalogo?.producto ?? null;

                if(!prod){
                    this.messages.error = 'Producto no encontrado en el catálogo.';
                    return;
                }
                this.addToCart(prod);
            },

            // ==== CARRITO Y ACCIONES ====
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
                } else {
                    const precioUnitario = Number(prod.precio);
                    this.cart.push({
                        producto_id: prod.id,
                        sku: prod.sku ?? null,
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

            // ==== CÁLCULOS ====
            recalculateCartPrices(totalUnidadesPedido){
                this.cart.forEach(item => {
                    const prod = this.productos.find(p => p.id === item.producto_id);
                    if(!prod) return;

                    const reglaVis = this.getReglaVisibilidad(prod);
                    let porcentaje = (reglaVis) 
                        ? Number(reglaVis.porcentaje ?? 0) 
                        : this.getPorcentajeDescuentoParaProducto(prod, totalUnidadesPedido);

                    const precioUnit = Number(item.precio_unitario_catalogo ?? 0);
                    const precioDesc = Number((precioUnit * (1 - (porcentaje/100))).toFixed(2));

                    item.porcentaje_descuento = porcentaje;
                    item.precio_con_descuento = precioDesc;
                    item.subtotal = precioDesc * Number(item.cantidad ?? 0);
                });
            },

            calculateTotals(){
                let totalUnidadesPedido = this.cart.reduce((a,b) => a + Number(b.cantidad ?? 0), 0);
                
                // Limpieza automática de productos por regla de visibilidad si bajan las unidades
                this.cart = this.cart.filter(item => {
                    const prod = this.productos.find(p => p.id === item.producto_id);
                    if(!prod) return true;
                    const reglaVis = this.getReglaVisibilidad(prod);
                    if(!reglaVis) return true;
                    const min = Number(reglaVis.puntaje_minimo ?? 0);
                    return (totalUnidadesPedido - item.cantidad) >= min;
                });

                totalUnidadesPedido = this.cart.reduce((a,b) => a + Number(b.cantidad ?? 0), 0);
                this.recalculateCartPrices(totalUnidadesPedido);

                this.subtotal = this.cart.reduce((a,b) => a + Number(b.subtotal ?? 0), 0);
                this.totalPuntos = this.cart.reduce((a,b) => a + (Number(b.puntos ?? 0) * Number(b.cantidad ?? 0)), 0);
                this.totalUnidades = totalUnidadesPedido;
                this.totalGanancias = this.cart.reduce((a,b) => {
                    const unit = Number(b.precio_unitario_catalogo ?? 0);
                    const desc = Number(b.precio_con_descuento ?? unit);
                    return a + (unit - desc) * Number(b.cantidad ?? 0);
                }, 0);

                this.calculateTotalGastos();
            },

            calculateTotalGastos(){
                this.totalGastos = this.gastosSeleccionados.reduce((total, gastoId) => {
                    const encontrado = this.gastosDisponibles.find(g => Number(g.id) === Number(gastoId));
                    return encontrado ? total + Number(encontrado.monto ?? 0) : total;
                }, 0);
            },

            // ==== PERFILES Y ENVÍO ====
            updateDatosPedido(){
                let vendedora = this.vendedorasPerfil.find(v => String(v.id) === String(this.vendedora_id));
                this.datosPedido.vendedora = {
                    nombre: vendedora?.name ?? null,
                    direccion: vendedora?.direccion ?? null,
                    zona: vendedora?.zona ?? null,
                };

                let lider = this.lideresPerfil.find(l => String(l.id) === String(this.lider_id));
                if (!lider && this.esLiderAutenticado && this.lideresPerfil.length) {
                    lider = this.lideresPerfil[0];
                    this.lider_id = lider?.id ?? null;
                }

                this.datosPedido.lider = {
                    nombre: lider?.name ?? null,
                    direccion: lider?.direccion ?? null,
                    zona: lider?.zona ?? null,
                };
            },

            async storePedido(){
                let res = await this.$wire.storePedido(
                    this.cart.map(i => ({
                        producto_id: i.producto_id,
                        sku: i.sku,
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
                this.storePedido().finally(() => {
                    if (!this.successModalOpen) this.isSubmitting = false;
                });
            },

            // ==== NOTIFICACIONES / UI ====
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
                if (this.temporizadoresBotonCatalogo[id]) clearTimeout(this.temporizadoresBotonCatalogo[id]);
                this.estadoBotonCatalogo = { ...this.estadoBotonCatalogo, [id]: 'agregado' };
                this.temporizadoresBotonCatalogo[id] = setTimeout(() => {
                    this.estadoBotonCatalogo = { ...this.estadoBotonCatalogo, [id]: null };
                }, 1200);
            },

            goToMisPedidos(){
                window.location.href = '/mis-pedidos';
            }
        }
    }
</script>
