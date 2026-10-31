<template>
  <NavBar />
  <div class="auth-wrapper">
  <div class="container">
    <h1>Dados de Treino</h1>
    <p class="subtitle">Registre seus treinos e associe a um profissional</p>

    <div v-if="mensagem" class="alert">{{ mensagem }}</div>

    <div class="grid-form-list">
      <form class="form-card" @submit.prevent="salvar">
        <label>Nome do treino</label>
        <input v-model="form.nome_treino" placeholder="Ex: Treino de pernas" required />

        <label>Tipo</label>
        <select v-model="form.tipo" required>
          <option value="">Selecione</option>
          <option>Força</option>
          <option>Cardio</option>
          <option>Mobilidade</option>
          <option>Funcional</option>
        </select>

        <label>Duração (min)</label>
        <input v-model="form.duracao_min" type="number" min="0" />

        <label>Data</label>
        <input v-model="form.data_treino" type="date" />

        <label>Profissional responsável</label>
        <select v-model="form.profissional_id">
          <option value="">Nenhum / autônomo</option>
          <option v-for="p in profissionais" :key="p.id" :value="p.id">{{ p.nome }} ({{ p.especialidade }})</option>
        </select>

        <label>Observações</label>
        <textarea v-model="form.observacoes" rows="2"></textarea>

        <button class="btn-primary" type="submit">Salvar treino</button>
      </form>

      <div class="list-card">
        <h3>Treinos registrados</h3>
        <p v-if="treinos.length === 0" class="sub">Nenhum treino registrado ainda.</p>
        <ul class="lista" v-else>
          <li v-for="t in treinos" :key="t.id">
            <strong>{{ t.nome_treino }}</strong>
            <span>{{ t.tipo }} · {{ t.data_treino }}<template v-if="t.duracao_min"> · {{ t.duracao_min }} min</template></span>
            <p v-if="t.profissional_nome" class="sub">Com {{ t.profissional_nome }}</p>
            <button type="button" class="btn-excluir" @click="excluir(t.id)">Excluir</button>
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

const treinos = ref([])
const profissionais = ref([])
const mensagem = ref('')
const form = reactive({
  nome_treino: '', tipo: '', duracao_min: '', data_treino: new Date().toISOString().slice(0, 10),
  profissional_id: '', observacoes: '',
})

async function carregar() {
  const [{ treinos: listaTreinos }, { profissionais: listaProfissionais }] = await Promise.all([
    api.listarTreinos(),
    api.listarProfissionais(),
  ])
  treinos.value = listaTreinos
  profissionais.value = listaProfissionais
}

async function salvar() {
  try {
    await api.criarTreino(form)
    mensagem.value = 'Treino registrado com sucesso!'
    form.nome_treino = ''
    form.tipo = ''
    form.duracao_min = ''
    form.observacoes = ''
    await carregar()
  } catch (e) {
    mensagem.value = e.message
  }
}

async function excluir(id) {
  if (!confirm('Excluir este treino?')) return

  try {
    await api.excluirTreino(id)
    mensagem.value = 'Treino excluído com sucesso!'
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

/* Brilhos ambientais */
.background-glow {
  position: absolute;
  z-index: -1;
  width: 450px;
  height: 450px;
  border-radius: 50%;
  filter: blur(110px);
  opacity: 0.17;
  pointer-events: none;
}

.glow-top {
  top: -250px;
  right: 8%;
  background: #8af45e;
}

.glow-bottom {
  bottom: -300px;
  left: 25%;
  background: #178a51;
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
