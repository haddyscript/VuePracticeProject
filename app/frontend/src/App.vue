<script setup lang="ts">
import Navbar from './components/Navbar.vue';
import Home from './components/Home.vue';
import FooterSection from './components/FooterSection.vue';
import AdminNavBar from './admin/AdminNavBar.vue';

import { onMounted, watch, computed } from 'vue';
import { useRoute } from 'vue-router'; // Import the route composable

const route = useRoute();

// Computed properties to evaluate path-based conditions
const isAdminRoute = computed(() => route.path.includes('/admin'));
const showAdminNavBar = computed(() =>
  isAdminRoute.value &&
  !['/admin/register', '/admin/login', '/admin/reset_password'].includes(route.path)
);

// Function to dynamically load resources for admin routes
const loadAdminResources = () => {
  const existingCSS = document.querySelector('link[href="/src/assets/css/portal.css"]');
  const existingScripts = Array.from(document.scripts).map((script) => script.src);

  if (!existingCSS) {
    const adminCSS = document.createElement('link');
    adminCSS.rel = 'stylesheet';
    adminCSS.href = '/src/assets/css/portal.css';
    document.head.appendChild(adminCSS);
  }

  const adminScripts = [
    '/src/assets/plugins/popper.min.js',
    '/src/assets/plugins/bootstrap/js/bootstrap.min.js',
    '/src/assets/plugins/chart.js/chart.min.js',
    '/src/assets/js/index-charts.js',
  ];

  adminScripts.forEach((src) => {
    if (!existingScripts.includes(src)) {
      const script = document.createElement('script');
      script.src = src;
      script.async = false; // Ensures the correct order of execution
      document.body.appendChild(script);
    }
  });
};

// Load resources on mount and watch for route changes
onMounted(() => {
  if (isAdminRoute.value) {
    loadAdminResources();
  }
});

watch(
  () => route.path,
  (newPath) => {
    if (newPath.includes('/admin')) {
      loadAdminResources();
    }
  }
);

</script>

<template>
  <div v-if="!isAdminRoute">
    <Navbar />
    <router-view />
    <FooterSection />
  </div>

  <div v-else>
    <AdminNavBar v-if="showAdminNavBar" />
    <router-view />
  </div>
</template>
