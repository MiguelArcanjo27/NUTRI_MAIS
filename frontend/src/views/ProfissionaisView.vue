<template>
  <NavBar />
  <div class="container">
    <h1>Profissionais da Área</h1>
    <p class="subtitle">Cadastre e encontre nutricionistas e personal trainers</p>

    <div v-if="mensagem" class="alert">{{ mensagem }}</div>

    <div class="grid-form-list">
      <form class="form-card" @submit.prevent="salvar">
        <label>Nome</label>
        <input v-model="form.nome" required />

        <label>E-mail</label>
        <input v-model="form.email" type="email" required />

        <label>Senha (mín. 6 caracteres)</label>
        <input v-model="form.senha" type="password" minlength="6" required />

        <label>Especialidade</label>
        <select v-model="form.especialidade" required>
          <option value="">Selecione</option>
          <option>Nutricionista</option>
          <option>Personal Trainer</option>
        </select>

        <label>Registro profissional (CRN/CREF)</label>
        <input v-model="form.registro" />

        <button class="btn-primary" type="submit">Cadastrar profissional</button>
      </form>

      <div class="list-card">
        <h3>Profissionais cadastrados</h3>
        <p v-if="profissionais.length === 0" class="sub">Nenhum profissional cadastrado ainda.</p>
        <ul class="lista" v-else>
          <li v-for="p in profissionais" :key="p.id">
            <strong>{{ p.nome }}</strong>
            <span>{{ p.especialidade }}<template v-if="p.registro_profissional"> · {{ p.registro_profissional }}</template></span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import NavBar from './NavBar.vue'
import { api } from '../services/api'

const profissionais = ref([])
const mensagem = ref('')
const form = reactive({ nome: '', email: '', senha: '', especialidade: '', registro: '' })

async function carregar() {
  const { profissionais: lista } = await api.listarProfissionais()
  profissionais.value = lista
}

async function salvar() {
  try {
    await api.criarProfissional(form)
    mensagem.value = 'Profissional cadastrado com sucesso!'
    form.nome = ''
    form.email = ''
    form.senha = ''
    form.especialidade = ''
    form.registro = ''
    await carregar()
  } catch (e) {
    mensagem.value = e.message
  }
}

onMounted(carregar)
</script>
