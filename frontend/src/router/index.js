import { createRouter, createWebHistory } from 'vue-router'
import { api } from '../services/api'

import LoginView from '../views/LoginView.vue'
import DashboardView from '../views/DashboardView.vue'
import DietaView from '../views/DietaView.vue'
import TreinoView from '../views/TreinoView.vue'
import EvolucaoView from '../views/EvolucaoView.vue'
import ProfissionaisView from '../views/ProfissionaisView.vue'

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', name: 'login', component: LoginView },
  { path: '/dashboard', name: 'dashboard', component: DashboardView, meta: { requiresAuth: true } },
  { path: '/dieta', name: 'dieta', component: DietaView, meta: { requiresAuth: true } },
  { path: '/treino', name: 'treino', component: TreinoView, meta: { requiresAuth: true } },
  { path: '/evolucao', name: 'evolucao', component: EvolucaoView, meta: { requiresAuth: true } },
  { path: '/profissionais', name: 'profissionais', component: ProfissionaisView, meta: { requiresAuth: true } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Guarda de navegação: confirma com o back-end se há sessão ativa
router.beforeEach(async (to) => {
  if (!to.meta.requiresAuth) return true

  try {
    const { usuario } = await api.eu()
    if (!usuario) return { name: 'login' }
  } catch {
    return { name: 'login' }
  }

  return true
})

export default router
