<template>
    <div class="w-100">
        <v-row>
            <v-col cols="12" md="1">
                <v-btn variant="outlined" @click="openDialogProduct()" block style="height: 100%;padding-top: 5px;padding-bottom: 5px;">
                    <v-icon icon="fas fa-solid fa-barcode" size="small"></v-icon>
                </v-btn>
            </v-col>
            <v-col cols="12" md="9" >
                <v-text-field
                    ref="searchInput"
                    v-model="strSearch"
                    label="Buscar por codigo o nombre"
                    hide-details/>
            </v-col>
            <v-col cols="12" md="2">
                <v-btn variant="outlined" @click="getProducts()" block style="height: 100%;padding-top: 5px;padding-bottom: 5px;">
                    ENTER - Agregar producto
                </v-btn>
            </v-col>
        </v-row>
        <div class="container-products bg-blue-grey-lighten-4">
            <v-row>
                <v-col cols="12" md="4">
                    <div style="padding: 10px;padding-right: 0px;">
                        <v-card title="Productos Favoritos" text="" style="height: calc(100vh - 330px);">
                            <v-row style="overflow: scroll;max-height: 100%;">
                                <v-col cols="4" v-for="item in productsFavorits">
                                    <v-img :width="200" aspect-ratio="1/1" cover block :src="`https://dummyimage.com/200x200/000/fff&text=${item.product.name}`" />
                                </v-col>
                            </v-row>
                            <p style="text-align: center;" v-show="productsFavorits.length == 0">No hay productos agregados como favoritos</p>
                        </v-card>
                    </div>
                </v-col>
                <v-col cols="12" md="8">
                    <div style="padding: 10px;padding-left: 0px;">
                        <v-card title="Productos a vender" text="" style="height: calc(100vh - 330px);">
                            <v-table density="compact">
                                <thead>
                                    <tr>
                                        <th> Codigo </th>
                                        <th> Producto </th>
                                        <th> Precio </th>
                                        <th> Cantidad </th>
                                        <th> Importe </th>
                                        <th> Existencia </th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in productsList" :key="item.name" >
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.calories }}</td>
                                    </tr>
                                </tbody>
                            </v-table>
                        </v-card>
                    </div>
                </v-col>
            </v-row>
        </div>
        <div class="footer-container">
            <v-row>
                <v-col cols="12" md="8" class="bg-green-lighten-3">

                </v-col>
                <v-col cols="12" md="4" class="bg-green-lighten-4">
                    <div class="text-h1 text-center">
                        {{ formatNumber(totalAmount) }}
                    </div>
                </v-col>
            </v-row>
        </div>

        <v-dialog v-model="dialogProducts" persistent fullscreen transition="dialog-bottom-transition">
            <template v-slot:default="{ isActive }">
                <v-toolbar>
                    <v-toolbar-title>Busqueda de productos</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn text="Cerrar" variant="text" @click="dialogProducts = false" ></v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card>
                    <div class="w-100">
                        <v-text-field ref="searchInputDialog" @keyup="getListDialog()" v-model="strSearchDialog" label="Buscar por codigo o nombre" hide-details/>
                        <v-table density="compact">
                            <thead>
                                <tr>
                                    <th> Producto </th>
                                    <th> Precio </th>
                                    <th> Departamento </th>
                                    <th> Existencia </th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in productsListDialog" :key="item.name" >
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.priceSale }}</td>
                                    <td>{{ item.depto }}</td>
                                    <td>{{( item.stock === null) ? "∞" : item.stock }}</td>
                                    <td>
                                        <v-col cols="auto">
                                            <v-btn density="compact" style="margin-right: 10px;" @click="addFav(item.id)">
                                                <v-icon v-if="item.isFavorite === null" icon="fas fa-regular fa-heart" size="small"></v-icon>
                                                <v-icon v-else icon="fas fa-regular fa-heart" size="small" color="red"></v-icon>
                                            </v-btn>
                                            <v-btn density="compact" icon="mdi-plus">
                                                <v-icon icon="fas fa-solid fa-cart-plus" size="small"></v-icon>
                                            </v-btn>
                                        </v-col>
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </div>
                </v-card>
            </template>
        </v-dialog>

        <v-snackbar v-model="toast" location="top end"> {{toastText}} </v-snackbar>
    </div>
</template>
<script>
export default {
    data() {
        let laravel = window.laravel;
        return {
            urlBase : laravel.url + 'pos',
            strSearch : "",
            totalAmount : 0,
            productsList : [],
            dialogProducts : false,
            strSearchDialog : "",
            productsListDialog : [],
            toast : false,
            toastText : "",
            productsFavorits : []
        }
    },
    mounted() {
        this.$refs.searchInput.focus();
        this.getFavs();
    },
    methods: {
        async getFavs(){
            try {
                const response = await axios.get(this.urlBase + '/getFavs');
                this.productsFavorits = response.data;
            } catch (error) { }
        },
        async addFav(idProd){
            try {
                const response = await axios.get(this.urlBase + '/setFav?idProduct=' + idProd);
                this.showToast(response.data.msg);
                this.getListDialog();
                this.getFavs();
            } catch (error) { }
        },
        openDialogProduct(){
            this.strSearchDialog = "";
            this.dialogProducts = true;
        },
        async getListDialog(){
            try {
                const response = await axios.get(this.urlBase + '/getProductsDialog?strSearchDialog=' + this.strSearchDialog);
                this.productsListDialog = response.data;
            } catch (error) { }
        },
        formatNumber(number){
            const formatoMoneda = number.toLocaleString('es-MX', {
                style: 'currency',
                currency: 'MXN',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            return formatoMoneda;
        },
        showToast(text){
            this.toast = true;
            this.toastText = text;
            setInterval(() => {
                this.toast = false;
            }, 2500);
        },
    },
}
</script>
<style>
.w-100{
    width:100%;
}
.container-products{
    height: calc(100vh - 262px);
}
.footer-container{
    height: 100px;
}
</style>
