<template>
  <header class="topbar">
    <div class="logo">Nutri<span>+</span></div>
    <nav>
      <router-link to="/dashboard">Dashboard</router-link>
      <router-link to="/dieta">Dieta</router-link>
      <router-link to="/treino">Treinos</router-link>
      <router-link to="/evolucao">Evolução</router-link>
      <router-link to="/profissionais">Profissionais</router-link>
      <a href="#" @click.prevent="sair">Sair</a>
    </nav>
  </header>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { api } from '../services/api'

const router = useRouter()

async function sair() {
  await api.logout()
  router.push('/login')
}
</script>
