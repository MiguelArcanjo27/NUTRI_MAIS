<template>
  <header class="topbar">
    <div class="logo">Nutri<span><img src="/logo.png" alt="logo" class="foto"></span></div>
    <nav ref="navRef">
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
import { nextTick, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import { api } from '../services/api'

const router = useRouter()
const navRef = ref(null)

watch(
  () => router.currentRoute.value.fullPath,
  async () => {
    await nextTick()

    const nav = navRef.value
    const activeLink = nav?.querySelector('.router-link-active')
    if (!nav || !activeLink || !window.matchMedia('(max-width: 600px)').matches) return

    const navRect = nav.getBoundingClientRect()
    const linkRect = activeLink.getBoundingClientRect()
    nav.scrollTo({
      left: nav.scrollLeft + linkRect.left - navRect.left - (nav.clientWidth - linkRect.width) / 2,
      behavior: 'smooth'
    })
  },
  { immediate: true }
)

async function sair() {
  await api.logout()
  router.push('/login')
}
</script>

<style scoped>
.foto {
  width: 24px;
  height: 24px;
  object-fit: cover;
  border-radius: 50%;
}

@media (max-width: 600px) {
  .topbar {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }

  nav {
    display: flex;
    width: 100%;
    gap: 16px;
    overflow-x: auto;
    overscroll-behavior-x: contain;
    scroll-snap-type: x mandatory;
    scroll-padding-inline: 10%;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
  }

  nav::-webkit-scrollbar {
    display: none;
  }

  nav > * {
    flex: 0 0 80%;
    text-align: center;
    white-space: nowrap;
    scroll-snap-align: center;
  }

}
</style>
