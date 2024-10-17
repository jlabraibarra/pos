<template lang="">
    <v-container>
        <div class="row">
            <div class="col">
                <v-btn variant="outlined" @click="this.$router.push({ name: 'Products' })">Regresar</v-btn>
            </div>
            <div class="col text-end">
                <h2>Agregar Producto</h2>
            </div>
        </div>

        <div style="margin-left: 5%; margin-right: 5%;margin-bottom: 20px;margin-top: 25px;">
            <v-alert v-show="alert.show" closable :title="alert.title" :text="alert.content" :type="alert.type" />
        </div>

        <v-row style="margin-top: 25px;">
            <v-col cols="12" md="4" >
                <v-text-field
                    v-model="product.code"
                    label="Codigo de producto"
                    hide-details
                    :disabled="disabledInput || disabledCodeEdit" />
                <small>Si se deja vacio se genera un codigo aleatorio de forma automatica</small>
            </v-col>
            <v-col cols="12" md="8" >
                <v-text-field
                    v-model="product.name"
                    label="Nombre del producto"
                    hide-details
                    :disabled="disabledInput" />
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12" md="4" >
                <v-text-field
                    v-model="product.cost"
                    label="Costo del producto"
                    hide-details
                    :disabled="disabledInput" />
            </v-col>
            <v-col cols="12" md="4" >
                <v-text-field
                    v-model="product.priceSale"
                    label="Precio de venta"
                    hide-details
                    :disabled="disabledInput" />
            </v-col>
            <v-col cols="12" md="4" >
                <v-text-field
                    v-model="product.priceSaleMin"
                    label="Precio de mayoreo"
                    hide-details
                    :disabled="disabledInput" />
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12" md="4" >
                <v-select
                    v-model="product.depto"
                    :items="listDeptos"
                    item-title="label"
                    item-value="id"
                    label="Seleccion el departamento"
                    single-line
                    :disabled="disabledInput"/>
            </v-col>
            <v-col cols="12" md="4" >
                <v-select
                    v-model="product.unit"
                    :items="listUnits"
                    item-title="label"
                    item-value="id"
                    label="Tipo de venta"
                    single-line
                    :disabled="disabledInput"/>
            </v-col>
            <v-col cols="12" md="4" >
                <v-text-field
                    v-model="product.inventory"
                    label="Cuanto hay existencia"
                    hide-details
                    :disabled="disabledInput" />
                <small>Si no requiere inventario, dejar vacio este campo</small>
            </v-col>
        </v-row>

        <v-progress-linear v-show="disabledInput" style="margin-top: 50px;" indeterminate></v-progress-linear>

        <div class="d-flex justify-end" style="margin-top: 50px;">
            <v-btn variant="outlined" @click="saveProduct()">Guardar</v-btn>
        </div>

    </v-container>
</template>
<script>
export default {
    data() {
        let laravel = window.laravel;
        return {
            urlBase : laravel.url,
            _token : laravel._token,
            product : {
                id : 0,
                code : "",
                name : "",
                depto : "",
                cost : "",
                priceSale : "",
                priceSaleMin : "",
                inventory : "",
                unit : "",
                _token : laravel._token,
            },
            listDeptos : [
                {
                    id : "",
                    label : "-- Seleccione un departamento --"
                },
                {
                    "id" : 1,
                    "label" : "Sin departamento",
                }
            ],
            listUnits : [
                {
                    id : "",
                    label : "-- Seleccione un tipo de venta --"
                },
                {
                    id : 1,
                    label : "Venta por unidad"
                },
                {
                    id : 2,
                    label : "Venta por peso"
                }
            ],
            alert : {
                show : false,
                title : "",
                content : "",
                type : ""
            },
            disabledInput : false,
            disabledCodeEdit : false
        }
    },
    mounted() {
        let id = this.$route.query.id;
        if(id !== undefined && id != 0 && id != "0"){
            this.getDataProd(id);
        }
    },
    methods: {
        async getDataProd(code){
            this.disabledInput = true;
            try {
                const response = await axios.post(this.urlBase + 'pos/product/data', {
                    code : code
                });
                this.alertShow("","Datos del producto cargados con exito", "success");
                this.product = response.data;
                this.disabledInput = false;
                this.disabledCodeEdit = true;
            } catch (error) {
                this.alertShow("","Ocurrio un error al cargar los datos, intentalo de nuevo mas tarde", "error");
                setTimeout(() => {
                    this.$router.push({ name: 'Products' })
                }, 2500);
            }
        },
        async saveProduct(){
            this.disabledInput = true;
            if (this.product.name == "") {
                this.alertShow("","El nombre de producto no debe estar vacio", "warning");
                this.disabledInput = false;
                return;
            }if (this.product.cost == "") {
                this.alertShow("","El costo del producto no debe estar vacio", "warning");
                this.disabledInput = false;
                return;
            }if (this.product.priceSale == "") {
                this.alertShow("","El precio de venta del producto no debe estar vacio", "warning");
                this.disabledInput = false;
                return;
            }if (this.product.priceSaleMin == "") {
                this.alertShow("","El precio de venta por mayoreo del producto no debe estar vacio", "warning");
                this.disabledInput = false;
                return;
            }if (this.product.depto == "") {
                this.alertShow("","Debe seleccionar un departamento", "warning");
                this.disabledInput = false;
                return;
            }if (this.product.unit == "") {
                this.alertShow("","Debe seleccionar un tipo de venta", "warning");
                this.disabledInput = false;
                return;
            }

            try {
                const response = await axios.post(this.urlBase + 'pos/product/save', this.product);
                if(response.data.status == "error"){
                    this.alertShow("",response.data.msg,"warning");
                    this.disabledInput = false;
                }else{
                    this.alertShow("",response.data.msg,"success");
                    setTimeout(() => {
                        this.$router.push({ name: 'Products' })
                    }, 1500);
                }
            } catch (error) {
                this.disabledInput = false;
            }
        },
        alertShow(title,content,type){
            this.alert.show = true;
            this.alert.title = title;
            this.alert.content = content;
            this.alert.type = type;
            setTimeout(() => {
                this.alert.show = false;
            }, 2800);
        }
    },
}
</script>
<style lang="">

</style>
