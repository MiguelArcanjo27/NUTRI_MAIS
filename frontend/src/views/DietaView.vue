<template>
  <NavBar />
  <div class="auth-wrapper">
  <div class="container">
    <h1>Cadastro de Dieta</h1>
    <p class="subtitle">Registre suas refeições e acompanhe as calorias</p>

    <div v-if="mensagem" class="alert">{{ mensagem }}</div>

    <div class="grid-form-list">
      <form class="form-card" @submit.prevent="salvar">
        <label>Nome da refeição</label>
        <input v-model="form.nome_refeicao" placeholder="Ex: Café da manhã" required />

        <label>Alimentos</label>
        <textarea v-model="form.alimentos" rows="3" placeholder="Ex: 2 ovos, aveia, banana" required></textarea>

        <label>Calorias (kcal)</label>
        <input v-model="form.calorias" type="number" min="0" />

        <label>Data</label>
        <input v-model="form.data_refeicao" type="date" />

        <button class="btn-primary" type="submit">Salvar refeição</button>
      </form>

      <div class="list-card">
        <h3>Refeições registradas</h3>
        <p v-if="dietas.length === 0" class="sub">Nenhuma refeição registrada ainda.</p>
        <ul class="lista" v-else>
          <li v-for="d in dietas" :key="d.id">
            <strong>{{ d.nome_refeicao }}</strong>
            <span>{{ d.data_refeicao }}<template v-if="d.calorias"> · {{ d.calorias }} kcal</template></span>
            <p class="sub">{{ d.alimentos }}</p>
            <button type="button" class="btn-excluir" @click="excluir(d.id)">Excluir</button>
          </li>
        </ul>
      </div>
    </div>
  </div>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import NavBar from './NavBar.vue'
import { api } from '../services/api'

const dietas = ref([])
const mensagem = ref('')
const form = reactive({ nome_refeicao: '', alimentos: '', calorias: '', data_refeicao: new Date().toISOString().slice(0, 10) })

async function carregar() {
  const { dietas: lista } = await api.listarDietas()
  dietas.value = lista
}

async function salvar() {
  try {
    await api.criarDieta(form)
    mensagem.value = 'Refeição registrada com sucesso!'
    form.nome_refeicao = ''
    form.alimentos = ''
    form.calorias = ''
    await carregar()
  } catch (e) {
    mensagem.value = e.message
  }
}

async function excluir(id) {
  if (!confirm('Excluir esta refeição?')) return

  try {
    await api.excluirDieta(id)
    mensagem.value = 'Refeição excluída com sucesso!'
    await carregar()
  } catch (e) {
    mensagem.value = e.message
  }
}

onMounted(carregar)
</script>

<style scoped>
.auth-wrapper {
  --green: #80e052;
  --green-light: #a0f078;
  --green-dark: #183d27;
  --text: #f4f8f3;
  --muted: #a4b7a8;

  position: relative;
  isolation: isolate;
  min-height: 100vh;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  box-sizing: border-box;
  padding: 48px;
  background:
    radial-gradient(ellipse at 76% 8%, #315c32 0%, transparent 34%),
    radial-gradient(ellipse at 13% 82%, #123b25 0%, transparent 36%),
    linear-gradient(125deg, #06150d 0%, #0b2013 50%, #07130c 100%);
  color: var(--text);
  font-family: Inter, system-ui, -apple-system, sans-serif;
}


.btn-excluir {
  margin-top: 8px;
  background: transparent;
  color: #ff8080;
  border: 1px solid #d95757;
  border-radius: 8px;
  padding: 6px 14px;
  font-size: 13px;
  cursor: pointer;
}
.btn-excluir:hover { background: #d95757; color: #fff; }
</style>
