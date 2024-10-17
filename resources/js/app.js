import './bootstrap';
import { createApp } from 'vue';

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import '@fortawesome/fontawesome-free/css/all.css';

const vuetify = createVuetify({
    components,
    directives,
    icons: {
        iconfont: 'fa'
    }
});

import routes from './routes';

import AuthComponent from './components/Auth.vue';
import PosComponent from './components/Pos.vue';

const app = createApp({
    components : {
        "auth-component" : AuthComponent,
        "pos-component" : PosComponent
    }
});

app.use(routes)
app.use(vuetify)

app.mount('#app');
