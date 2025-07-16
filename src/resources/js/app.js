import "./bootstrap";
import { createApp } from "vue";
import CustomerApp from "./components/CustomerApp.vue";

// Create Vue app and mount it
const app = createApp(CustomerApp);
app.mount("#app");
