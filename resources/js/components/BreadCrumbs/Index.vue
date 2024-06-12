<template>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mt-3">
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <router-link to="/">Home</router-link>
        </li>
        <li  v-for="(route, index) in breadcrumbRoutes" :key="index" class="breadcrumb-item" :class="{ active: route.meta.active }">
          <router-link v-if="!route.meta.active" :to="route.path">
            {{ route.meta.breadcrumb }}
          </router-link>
          <span v-else>{{ route.meta.breadcrumb }}</span>
        </li>
      </ol>
    </nav>
  </template>
  
  <script>
  export default {
    name: 'Breadcrumbs',
    computed: {
      breadcrumbRoutes() {
        const routes = this.$route.matched.filter((route) => route.meta && route.meta.breadcrumb);
        if(routes.length){
          routes[routes?.length - 1].meta.active = true;
        }
        return routes;
      },
    },
  };
  </script>
  <style scoped>
.breadcrumb-item a {
  text-decoration: none;
  color: inherit;
}
  </style>