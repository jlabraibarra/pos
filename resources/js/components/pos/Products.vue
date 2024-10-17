<template lang="">
    <div style="padding:3rem;">
        <div class="row">
            <div class="col">
                <h2>Productos</h2>
            </div>
            <div class="col text-end">
                <v-btn variant="outlined" @click="this.$router.push({ name: 'add-product' , query: { id: 0 }})">Agregar Producto</v-btn>
            </div>
        </div>

        <div style="padding-left:1rem; padding-right:1rem;margin-top:1rem;margin-bottom:1rem;">

            <v-row>
                <v-col cols="12" md="10" >
                    <v-text-field
                        v-model="strSearch"
                        label="Buscar por codigo o nombre"
                        hide-details/>
                </v-col>
                <v-col cols="12" md="2">
                    <v-btn variant="outlined" @click="getProducts()" block>
                        <v-icon icon="fas fa-solid fa-search" size="small"></v-icon>
                    </v-btn>
                </v-col>
            </v-row>
        </div>

        <v-progress-linear v-show="showProgressBar" style="margin-top: 50px;" indeterminate></v-progress-linear>

        <v-table fixed-header >
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Departamento</th>
                    <th>Costo</th>
                    <th>P. Venta</th>
                    <th>P. Mayoreo</th>
                    <th>Inventario</th>
                    <th>Unidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="products.length == 0">
                    <th colspan="9" style="text-align: center;">No hay productos registrados</th>
                </tr>
                <tr v-else v-for="prod in products">
                    <th>{{prod.code}}</th>
                    <th>{{prod.name}}</th>
                    <th>{{prod.depto}}</th>
                    <th>{{formatNumber(prod.price)}}</th>
                    <th>{{formatNumber(prod.priceSale)}}</th>
                    <th>{{formatNumber(prod.priceSaleMin)}}</th>
                    <th>{{(prod.inventory == 1) ? 0 : "∞"}}</th>
                    <th>{{(prod.unit == 1) ? "Unidad" : "Por peso"}}</th>
                    <th class="d-flex">
                        <v-btn @click="this.$router.push({ name: 'add-product' , query: { id: prod.code }})" density="compact" class="mr-2">
                            <v-icon icon="fas fa-regular fa-pen-to-square" size="small"></v-icon>
                        </v-btn>
                        <v-btn @click="confirmDelete(prod)" density="compact" class="mr-2">
                            <v-icon  icon="fas fa-solid fa-trash-can" size="small"></v-icon>
                        </v-btn>
                        <v-btn v-show="(prod.inventory == 1)" density="compact">
                            <v-icon icon="fas fa-solid fa-truck-ramp-box" size="small"></v-icon>
                        </v-btn>
                    </th>
                </tr>
            </tbody>
        </v-table>

        <v-bottom-sheet inset v-model="showSheet">
            <v-card :title="titleSheet">
                <v-row style="margin-bottom:2rem;">
                    <v-col cols="12" md="6" style="text-align:center;">
                        <v-btn variant="outlined" @click="showSheet = false" >
                            Cancelar
                        </v-btn>
                    </v-col>
                    <v-col cols="12" md="6" style="text-align:center;">
                        <v-btn variant="outlined" @click="deleteItem()" >
                            Eliminar
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card>
        </v-bottom-sheet>

        <v-snackbar v-model="toast" location="top end"> {{toastText}} </v-snackbar>

    </div>
</template>
<script>
export default {
    data() {
        let laravel = window.laravel;
        return {
            urlBase : laravel.url,
            products : [],
            delete : [],
            strSearch : "",
            showProgressBar : false,
            showSheet : false,
            titleSheet : "",
            toast : false,
            toastText : ""
        }
    },
    mounted() {
        this.getProducts();
    },
    methods: {
        async getProducts(){
            this.showProgressBar = true;
            try {
                const response = await axios.get(this.urlBase + 'pos/product?strSearch=' + this.strSearch);
                this.products = response.data;
            } catch (error) { }
            this.showProgressBar = false;
        },
        confirmDelete(prod){
            this.showSheet = true;
            this.delete = prod;
            this.titleSheet = '¿Esta seguro que desea eliminar el producto '+this.delete.name+' ['+this.delete.code+']?';
        },
        async deleteItem(){
            try {
                const response = await axios.post(this.urlBase + 'pos/product/delete', this.delete);
                this.showToast(response.data.msg);
            } catch (error) { }
        },
        showToast(text){
            this.toast = true;
            this.toastText = text;
            setInterval(() => {
                this.toast = false;
            }, 2500);
        },
        formatNumber(number){
            const formatoMoneda = number.toLocaleString('es-MX', {
                style: 'currency',
                currency: 'MXN',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            return formatoMoneda;
        }
    },
}
</script>
<style lang="">

</style>
