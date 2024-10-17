<template lang="">
    <div style="padding:3rem;">
        <h2>Inventario</h2>

        <v-progress-linear v-show="showProgressBar" style="margin-top: 50px;" indeterminate></v-progress-linear>

        <v-table fixed-header >
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Estatus</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="inventories.length == 0">
                    <th colspan="5" style="text-align: center;">No hay productos registrados</th>
                </tr>
                <tr v-else v-for="inv in inventories">
                    <th>{{inv.product.code}}</th>
                    <th>{{inv.product.name}}</th>
                    <th>
                        <v-chip v-if="inv.stock >= inv.stockMax" color="green">Sobrestock</v-chip>
                        <v-chip v-else-if="inv.stock <= inv.stockMin" color="green">Sin stock</v-chip>
                        <v-chip v-else color="green">Con Stock</v-chip>
                    </th>
                    <th>{{inv.stock}}</th>
                    <th class="d-flex">

                    </th>
                </tr>
            </tbody>
        </v-table>

    </div>
</template>
<script>
export default {
    data() {
        let laravel = window.laravel;
        return {
            urlBase : laravel.url + 'pos/inventory',
            inventories : [],
            showProgressBar : false
        }
    },
    mounted() {
        this.getInventories();
    },
    methods: {
        async getInventories(){
            this.showProgressBar = true;
            try {
                const response = await axios.get(this.urlBase + '/');
                this.inventories = response.data;
                this.showProgressBar = false;
            } catch (error) { }
        }
    },
}
</script>
<style lang="">

</style>
