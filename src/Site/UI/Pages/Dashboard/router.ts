import { createRouter, createWebHistory } from 'vue-router';
import Home from './Home.vue';
import Settings from './Settings.vue';

const routes = [
  { path: '/dashboard', component: Home },
  { path: '/dashboard/settings', component: Settings }
];

export const router = createRouter({
  history: createWebHistory(),
  routes
});
