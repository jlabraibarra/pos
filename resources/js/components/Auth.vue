<template>
    <div class="d-flex justify-center align-center" style="height: calc(100vh);">
        <div style="width: 500px;">
            <v-card
                title="Inicio de sesión"
                variant="tonal"
                elevation="16"
                class="grey-darken-4 text-center bg-white"
                :loading="loadingCardLogin">

                <div style="margin-left: 5%; margin-right: 5%;margin-bottom: 20px;">
                    <v-alert v-show="alert.show" closable :title="alert.title" :text="alert.content" :type="alert.type" />
                </div>

                <div class="d-flex justify-center">
                    <div style="width: 80%;">
                        <v-text-field
                            hide-details="auto"
                            label="Nombre de usuario"
                            v-model="userInput.value"
                            :disabled="userInput.disabled"
                            />
                        <br>
                        <v-text-field
                            type="password"
                            hide-details="auto"
                            label="Contraseña"
                            v-model="passInput.value"
                            :disabled="passInput.disabled"
                            />
                    </div>
                </div>
                <v-card-actions class="d-flex justify-center">
                    <div style="width: 80%;" class="d-flex justify-end">
                        <v-btn variant="outlined" @click="login()" :disabled="btnDisabled">
                            Ingresar
                        </v-btn>
                    </div>
                </v-card-actions>
            </v-card>
        </div>
    </div>
</template>
<script>
export default {
    name: 'AuthComponent',
    data() {
        let laravel = window.laravel;
        return {
            urlBase : laravel.url,
            _token : laravel._token,
            imageBackgroundLogin : laravel.url + "/images/background_login.jpg",
            loadingCardLogin : false,
            userInput : {
                disabled : false,
                value : ""
            },
            passInput : {
                disabled : false,
                value : ""
            },
            alert : {
                show : false,
                title : "",
                content : "",
                type : ""
            },
            btnDisabled : false
        }
    },
    methods: {
        async login(){
            this.inputBlock(true);

            if(this.userInput.value == ""){
                this.alertShow("","El nombre de usuario no debe estar vacio","warning");
                this.inputBlock(false);
                return;
            }if(this.passInput.value == ""){
                this.alertShow("","La contraseña no debe estar vacia","warning");
                this.inputBlock(false);
                return;
            }

            try {
                const response = await axios.post(this.urlBase + 'login/access', {
                    'username' : this.userInput.value,
                    'password' : this.passInput.value
                });

                if(response.data.status == "error"){
                    this.alertShow("",response.data.msg,"warning");
                }else{
                    // Lo mandamos al POS
                    this.alertShow("",response.data.msg,"success");
                    setTimeout(() => {
                        window.location = "/pos"
                    }, 1500);
                    return;
                }

                this.inputBlock(false)
            } catch (error) {
                this.inputBlock(false)
                console.error('Error:', error.response ? error.response.data : error.message);
            }

        },
        inputBlock(mode){
            this.loadingCardLogin = mode;
            this.userInput.disabled = mode;
            this.passInput.disabled = mode;
            this.btnDisabled = mode;
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
