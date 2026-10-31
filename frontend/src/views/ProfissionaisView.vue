<template>
  <NavBar />
  <div class="auth-wrapper">
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
</style>

