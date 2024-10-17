<template lang="">
    <v-app>
        <v-container>
            <v-system-bar window color="primary">
                <v-row no-gutters>
                    <v-col class="d-flex justify-start" cols="12" sm="12" md="6" lg="6">
                        Le atiende: {{ user.name }}
                    </v-col>
                    <v-col class="d-flex justify-end" cols="12" sm="12" md="6" lg="6">
                        <span class="ms-2">{{currentDate}} - {{currentTime}}</span>
                    </v-col>
                </v-row>
            </v-system-bar>
        </v-container>
        <v-toolbar border density="compact">
            <v-row no-gutters>
                <v-col cols="12" sm="12" md="6" lg="6">
                    <v-btn @click="changeView('Sales')" variant="outlined" class="btn-bar-1" style="margin-left:10px;">
                        Ventas
                    </v-btn>
                    <v-btn @click="changeView('Products')" variant="outlined" class="btn-bar-1">
                        Productos
                    </v-btn>
                    <v-btn @click="changeView('Inventory')" variant="outlined" class="btn-bar-1">
                        Inventario
                    </v-btn>
                </v-col>
                <v-col class="d-flex justify-end" cols="12" sm="12" md="6" lg="6">
                    <v-btn variant="outlined" class="btn-bar-1"> Corte </v-btn>
                    <v-btn variant="outlined" class="btn-bar-1"> Reportes </v-btn>
                    <v-btn variant="outlined" class="btn-bar-1"> Configuracion </v-btn>
                    <v-btn variant="outlined" class="btn-bar-1" @click="logout()"> Cerrar sesion </v-btn>
                </v-col>
            </v-row>
        </v-toolbar>
        <div class="content-container-main">
            <router-view></router-view>
        </div>
    </v-app>
</template>
<script>
export default {
    name: 'PosComponent',
    data() {
        let laravel = window.laravel;
        return {
            user : laravel.user,
            currentDate : "",
            currentTime: ""
        }
    },
    mounted() {
        this.updateTime();
        setInterval(this.updateTime, 1000);
        this.setDate();
    },
    methods: {
        setDate(){
            const now = new Date();
            let day = now.getDate();
            let month = now.getMonth() + 1;
            const year = now.getFullYear();
            day = day < 10 ? '0' + day : day;
            month = month < 10 ? '0' + month : month;
            this.currentDate = `${day}/${month}/${year}`;
        },
        updateTime() {
            const now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes();
            let seconds = now.getSeconds();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;
            this.currentTime = `${hours}:${minutes}:${seconds} ${ampm}`;
        },
        logout(){
            window.location = "/login/logout";
        },
        changeView(view){
            this.$router.push({ name: view });
        }
    },
}
</script>
<style>
.btn-bar-1{
    margin-right: 10px;
}

.content-container-main{
    height: calc(100vh - 82px);
}
</style>
