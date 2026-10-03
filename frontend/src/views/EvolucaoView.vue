<template>
  <NavBar />
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
.grafico { width: 100%; height: 120px; }
</style>
