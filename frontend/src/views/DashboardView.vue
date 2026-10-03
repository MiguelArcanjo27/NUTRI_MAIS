<template>
  <NavBar />
  <div class="container dashboard">
    <header class="greeting">
      <div>
        <h1>Olá, {{ usuario?.nome || '...' }}!</h1>
        <p class="subtitle">{{ dataFormatada }}</p>
      </div>
    </header>

    <section class="card">
      <h2>Resumo do Treino</h2>
      <div class="resumo-content">
        <div class="donut" :style="{ '--pct': percentualCalorias + '%' }">
          <div class="donut-centro">🔥</div>
        </div>
        <div class="resumo-info">
          <p class="label">Calorias queimadas</p>
          <p class="valor">{{ calorias.queimadas }} <span class="sub-inline">/ {{ calorias.meta }} kcal</span></p>
          <p class="sub">Ativo: <strong>{{ calorias.ativo }}</strong> · Repouso: <strong>{{ calorias.repouso }}</strong></p>
        </div>
      </div>
    </section>

    <section class="card passos">
      <div class="passos-info">
        <p class="label">👟 Passos</p>
        <p class="valor">{{ passos.atual.toLocaleString('pt-BR') }} <span class="sub-inline">/ {{ passos.meta.toLocaleString('pt-BR') }}</span></p>
        <p class="sub">{{ passos.km }} km</p>
      </div>
      <div class="ring-wrap">
        <svg viewBox="0 0 80 80">
          <circle cx="40" cy="40" r="34" fill="none" stroke="#222" stroke-width="8" />
          <circle
            cx="40" cy="40" r="34" fill="none" stroke="#7ED957" stroke-width="8"
            stroke-linecap="round"
            :stroke-dasharray="circunferencia"
            :stroke-dashoffset="offsetPassos"
            transform="rotate(-90 40 40)"
          />
        </svg>
        <span class="ring-label">{{ percentualPassos }}%</span>
      </div>
    </section>

    <section class="card">
      <div class="freq-header">
        <h2>Frequência Cardíaca</h2>
        <span class="sub">{{ treinoAtual }}</span>
      </div>
      <p class="freq-atual">
        Atual: <strong class="destaque">{{ frequencia.atual }} bpm</strong>
        <span class="sub"> · Média: {{ frequencia.media }} · Pico: {{ frequencia.pico }}</span>
      </p>
      <svg viewBox="0 0 300 100" class="grafico-freq" preserveAspectRatio="none">
        <polyline :points="pontosFrequencia" fill="none" stroke="#7ED957" stroke-width="2" />
      </svg>
      <div class="zonas">
        <span class="zona fat-burn">Queima de Gordura</span>
        <span class="zona aerobico">Aeróbico</span>
        <span class="zona anaerobico">Anaeróbico</span>
      </div>
    </section>

    <section class="card">
      <h2>Treinos Recentes</h2>
      <div v-if="treinosRecentes.length === 0" class="sub">Nenhum treino registrado ainda.</div>
      <div class="lista-treinos" v-else>
        <div class="treino-item" v-for="t in treinosRecentes" :key="t.id">
          <strong>{{ t.nome_treino }}</strong>
          <span class="sub">{{ t.tipo }} · {{ t.duracao_min ? t.duracao_min + ' min' : '' }} · {{ t.data_treino }}</span>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import NavBar from './NavBar.vue'
import { api } from '../services/api'

const usuario = ref(null)
const treinosRecentes = ref([])

const dataFormatada = new Date().toLocaleDateString('pt-BR', {
  weekday: 'long', day: 'numeric', month: 'long',
})

// Estes três blocos (calorias, passos, frequência) ainda não têm endpoint próprio —
// use estes valores de exemplo até criar essa integração no back-end.
const calorias = { queimadas: 580, ta: 2200, ativo: 410, repouso: 170 }
const passos = { atual: 12450, meta: 15000, km: 8.9 }
const frequencia = { atual: 142, media: 135, pico: 168 }
const treinoAtual = 'Treino de hoje'

const percentualCalorias = computed(() => Math.round((calorias.queimadas / calorias.ta) * 100))
const circunferencia = 2 * Math.PI * 34
const percentualPassos = computed(() => Math.round((passos.atual / passos.meta) * 100))
const offsetPassos = computed(() => circunferencia * (1 - passos.atual / passos.meta))

const pontosFrequencia = computed(() => {
  const valores = [80, 90, 85, 100, 95, 110, 105, 120, 115, 130, 125, 140, 135, 142]
  const passoX = 300 / (valores.length - 1)
  return valores.map((v, i) => `${i * passoX},${100 - v * 0.6}`).join(' ')
})

onMounted(async () => {
  const { usuario: u } = await api.eu()
  usuario.value = u

  const { treinos } = await api.listarTreinos()
  treinosRecentes.value = treinos.slice(0, 3)
})
</script>

<style scoped>
.dashboard { display: flex; flex-direction: column; gap: 16px; }
.greeting h1 { margin: 0; font-size: 22px; }

.resumo-content { display: flex; align-items: center; gap: 18px; }
.donut {
  --pct: 0%;
  width: 90px; height: 90px; border-radius: 50%;
  background: conic-gradient(var(--green) var(--pct), #222 var(--pct) 100%);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.donut-centro {
  width: 66px; height: 66px; border-radius: 50%;
  background: var(--bg-card);
  display: flex; align-items: center; justify-content: center;
  font-size: 22px;
}
.label { margin: 0; font-size: 13px; color: var(--text-muted); }
.valor { margin: 2px 0; font-size: 22px; font-weight: 800; }
.sub-inline { font-size: 14px; font-weight: 400; color: var(--text-muted); }

.passos { display: flex; align-items: center; justify-content: space-between; }
.ring-wrap { position: relative; width: 80px; height: 80px; }
.ring-label {
  position: absolute; inset: 0;
  display: flex; align-items: center; justify-content: center;
  font-weight: 700; font-size: 13px;
}

.freq-header { display: flex; justify-content: space-between; align-items: baseline; }
.freq-atual { margin: 4px 0 10px; font-size: 13px; }
.destaque { color: var(--green); font-size: 16px; }
.grafico-freq { width: 100%; height: 90px; }
.zonas { display: flex; gap: 8px; margin-top: 8px; }
.zona { flex: 1; text-align: center; font-size: 10px; padding: 4px 0; border-radius: 6px; color: #06120a; font-weight: 700; }
.fat-burn { background: #ffb347; }
.aerobico { background: var(--green); }
.anaerobico { background: #ff6b6b; }

.lista-treinos { display: flex; flex-direction: column; gap: 10px; }
.treino-item { display: flex; flex-direction: column; gap: 2px; padding-bottom: 10px; border-bottom: 1px solid var(--border); }
.treino-item:last-child { border-bottom: none; padding-bottom: 0; }
</style>
