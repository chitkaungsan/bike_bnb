import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
  broadcaster: "pusher",
  key: import.meta.env.VITE_PUSHER_APP_KEY,     // must match your .env PUSHER_APP_KEY
  cluster: "ap1",                               // your Pusher cluster
  forceTLS: true,                               // use https (required by Pusher)
  disableStats: true,
  // Remove wsHost & wsPort — not used for cloud Pusher!

  authEndpoint: "/broadcasting/auth",
  auth: {
    headers: {
      Authorization: `Bearer ${localStorage.getItem("token")}`, // Passport token
      Accept: "application/json",
    },
  },
});