<template>
  <div class="auth-wrapper">
    <div class="card auth-card">
      <h1>Nutri<span style="color: var(--green)">+</span></h1>
      <p class="subtitle">Sua evolução, organizada.</p>

      <div v-if="erro" class="alert alert-erro">{{ erro }}</div>

      <div class="tabs">
        <button :class="{ active: aba === 'login' }" @click="aba = 'login'">Entrar</button>
        <button :class="{ active: aba === 'cadastro' }" @click="aba = 'cadastro'">Criar conta</button>
      </div>

      <form v-if="aba === 'login'" @submit.prevent="fazerLogin">
        <label>E-mail</label>
        <input v-model="loginForm.email" type="email" required />
        <label>Senha</label>
        <input v-model="loginForm.senha" type="password" required />
        <button class="btn-primary" type="submit">Entrar</button>
      </form>

      <form v-else @submit.prevent="fazerCadastro">
        <label>Nome</label>
        <input v-model="cadastroForm.nome" type="text" required />
        <label>E-mail</label>
        <input v-model="cadastroForm.email" type="email" required />
        <label>Senha (mín. 6 caracteres)</label>
        <input v-model="cadastroForm.senha" type="password" minlength="6" required />
        <button class="btn-primary" type="submit">Criar conta</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { api } from '../services/api'

const router = useRouter()
const aba = ref('login')
const erro = ref('')

const loginForm = reactive({ email: '', senha: '' })
const cadastroForm = reactive({ nome: '', email: '', senha: '' })

async function fazerLogin() {
  erro.value = ''
  try {
    await api.login(loginForm.email, loginForm.senha)
    router.push('/dashboard')
  } catch (e) {
    erro.value = e.message
  }
}

async function fazerCadastro() {
  erro.value = ''
  try {
    await api.cadastrar(cadastroForm.nome, cadastroForm.email, cadastroForm.senha)
    router.push('/dashboard')
  } catch (e) {
    erro.value = e.message
  }
}
</script>

<style scoped>
.auth-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
.auth-card { width: 100%; max-width: 380px; text-align: center; }
.tabs { display: flex; margin: 24px 0 20px; border-radius: 8px; overflow: hidden; border: 1px solid var(--border); }
.tabs button {
  flex: 1; padding: 10px; background: transparent; color: var(--text-muted);
  border: none; cursor: pointer; font-weight: 600;
}
.tabs button.active { background: var(--green); color: #06120a; }
form { text-align: left; }
</style>
