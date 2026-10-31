<template>
  <NavBar />
  <div class="auth-wrapper">
  <div class="container">
    <h1>Demonstração de Evolução</h1>
    <p class="subtitle">Acompanhe peso e percentual de gordura ao longo do tempo</p>

    <div v-if="mensagem" class="alert">{{ mensagem }}</div>

    <div class="grid-form-list">
      <form class="form-card" @submit.prevent="salvar">
        <label>Data</label>
        <input v-model="form.data_registro" type="date" />

        <label>Peso (kg)</label>
        <input v-model="form.peso" type="number" step="0.1" required />

        <label>Percentual de gordura (%)</label>
        <input v-model="form.percentual_gordura" type="number" step="0.1" />

        <label>Observação</label>
        <textarea v-model="form.observacao" rows="2"></textarea>

        <button class="btn-primary" type="submit">Registrar</button>
      </form>

      <div class="list-card">
        <h3>Gráfico de evolução (peso)</h3>
        <p v-if="registros.length === 0" class="sub">Nenhum registro ainda. Adicione seu primeiro peso.</p>
        <svg v-else viewBox="0 0 300 120" class="grafico" preserveAspectRatio="none">
          <polyline :points="pontos" fill="none" stroke="#7ED957" stroke-width="2" />
        </svg>
      </div>
    </div>
  </div>
  </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import NavBar from './NavBar.vue'
import { api } from '../services/api'

const registros = ref([])
const mensagem = ref('')
const form = reactive({ data_registro: new Date().toISOString().slice(0, 10), peso: '', percentual_gordura: '', observacao: '' })

async function carregar() {
  const { registros: lista } = await api.listarEvolucao()
  registros.value = lista
}

async function salvar() {
  try {
    await api.criarEvolucao(form)
    mensagem.value = 'Registro de evolução salvo com sucesso!'
    form.peso = ''
    form.percentual_gordura = ''
    form.observacao = ''
    await carregar()
  } catch (e) {
    mensagem.value = e.message
  }
}

const pontos = computed(() => {
  const pesos = registros.value.map((r) => parseFloat(r.peso))
  if (pesos.length === 0) return ''
  const min = Math.min(...pesos)
  const max = Math.max(...pesos) || min + 1
  const passoX = 300 / Math.max(pesos.length - 1, 1)
  return pesos.map((p, i) => {
    const y = 110 - ((p - min) / (max - min || 1)) * 100
    return `${i * passoX},${y}`
  }).join(' ')
})

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
.grafico { width: 100%; height: 120px; }
</style>
