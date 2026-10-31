```vue
<template>
  <div class="auth-wrapper">
    <!-- Elementos decorativos do fundo -->
    <div class="background-glow glow-top"></div>
    <div class="background-glow glow-bottom"></div>
    <div class="leaf-decoration leaf-top"></div>
    <div class="leaf-decoration leaf-bottom"></div>

    <!-- Conteúdo à esquerda -->
    <aside class="side-content">
      <div class="brand-mini">
        <span class="brand-symbol">✦</span>
        <span>Nutri <img src="/logo.png" alt="logo" class="foto-lateral"></span>
      </div>

      <div class="features">
        <div class="feature">
          <span class="feature-icon">♧</span>
          <span>NUTRIÇÃO</span>
        </div>

        <div class="feature">
          <span class="feature-icon">⚯</span>
          <span>TREINO</span>
        </div>

        <div class="feature">
          <span class="feature-icon">↗</span>
          <span>EVOLUÇÃO</span>
        </div>
      </div>

      <p class="side-description">
        Pequenas escolhas.<br />
        Grandes transformações.
      </p>
    </aside>

    <!-- Área principal -->
    <main class="auth-main">
      <div class="auth-card">
        <div class="brand">
          <h1>Nutri <img src="/logo.png" alt="logo" class="foto"></h1>
          <p class="subtitle">Sua saúde, em evolução.</p>
        </div>

        <div v-if="erro" class="alert alert-erro">
          {{ erro }}
        </div>

        <div class="tabs">
          <button
            type="button"
            :class="{ active: aba === 'login' }"
            @click="aba = 'login'; erro = ''"
          >
            Entrar
          </button>

          <button
            type="button"
            :class="{ active: aba === 'cadastro' }"
            @click="aba = 'cadastro'; erro = ''"
          >
            Criar conta
          </button>
        </div>

        <!-- Formulário de login -->
        <form v-if="aba === 'login'" @submit.prevent="fazerLogin">
          <div class="form-group">
            <label for="login-email">E-mail</label>
            <div class="input-wrapper">
              <span class="input-icon">✉</span>
              <input
                id="login-email"
                v-model="loginForm.email"
                type="email"
                placeholder="Digite seu e-mail"
                autocomplete="email"
                required
              />
            </div>
          </div>

          <div class="form-group">
            <label for="login-senha">Senha</label>
            <div class="input-wrapper">
              <span class="input-icon">♙</span>
              <input
                id="login-senha"
                v-model="loginForm.senha"
                :type="mostrarSenha ? 'text' : 'password'"
                placeholder="Digite sua senha"
                autocomplete="current-password"
                required
              />
              <button
                type="button"
                class="toggle-password"
                @click="mostrarSenha = !mostrarSenha"
                :aria-label="mostrarSenha ? 'Ocultar senha' : 'Mostrar senha'"
              >
                {{ mostrarSenha ? '◉' : '◎' }}
              </button>
            </div>
          </div>

          <button class="btn-primary" type="submit">
            Entrar <span>→</span>
          </button>
        </form>

        <!-- Formulário de cadastro -->
        <form v-else @submit.prevent="fazerCadastro">
          <div class="form-group">
            <label for="cadastro-nome">Nome completo</label>
            <div class="input-wrapper">
              <span class="input-icon">♙</span>
              <input
                id="cadastro-nome"
                v-model="cadastroForm.nome"
                type="text"
                placeholder="Digite seu nome"
                autocomplete="name"
                required
              />
            </div>
          </div>

          <div class="form-group">
            <label for="cadastro-email">E-mail</label>
            <div class="input-wrapper">
              <span class="input-icon">✉</span>
              <input
                id="cadastro-email"
                v-model="cadastroForm.email"
                type="email"
                placeholder="Digite seu e-mail"
                autocomplete="email"
                required
              />
            </div>
          </div>

          <div class="form-group">
            <label for="cadastro-senha">Senha (mín. 6 caracteres)</label>
            <div class="input-wrapper">
              <span class="input-icon">♙</span>
              <input
                id="cadastro-senha"
                v-model="cadastroForm.senha"
                :type="mostrarSenha ? 'text' : 'password'"
                placeholder="Crie uma senha"
                autocomplete="new-password"
                minlength="6"
                required
              />
              <button
                type="button"
                class="toggle-password"
                @click="mostrarSenha = !mostrarSenha"
                :aria-label="mostrarSenha ? 'Ocultar senha' : 'Mostrar senha'"
              >
                {{ mostrarSenha ? '◉' : '◎' }}
              </button>
            </div>
          </div>

          <button class="btn-primary" type="submit">
            Criar conta <span>→</span>
          </button>
        </form>

        <p class="card-footer">
          Sua jornada começa com uma escolha.
        </p>
      </div>

      <div class="bottom-caption">
        <span></span>
        DISCIPLINA HOJE, RESULTADOS AMANHÃ.
      </div>
    </main>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { api } from '../services/api'

const router = useRouter()

const aba = ref('login')
const erro = ref('')
const mostrarSenha = ref(false)

const loginForm = reactive({
  email: '',
  senha: ''
})

const cadastroForm = reactive({
  nome: '',
  email: '',
  senha: ''
})

async function fazerLogin() {
  erro.value = ''

  try {
    await api.login(loginForm.email, loginForm.senha)
    router.push('/dashboard')
  } catch (e) {
    erro.value = e.message || 'Não foi possível entrar. Tente novamente.'
  }
}

async function fazerCadastro() {
  erro.value = ''

  try {
    await api.cadastrar(
      cadastroForm.nome,
      cadastroForm.email,
      cadastroForm.senha
    )

    router.push('/dashboard')
  } catch (e) {
    erro.value = e.message || 'Não foi possível criar sua conta.'
  }
}
</script>

<style scoped>
.foto {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 50%;
}
.foto-lateral {
  width: 24px;
  height: 24px;
  object-fit: cover;
  border-radius: 50%;
}
/* Fundo principal */
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

/* Formas orgânicas decorativas */
.leaf-decoration {
  position: absolute;
  z-index: -1;
  width: 210px;
  height: 280px;
  border: 1px solid rgba(128, 224, 82, 0.12);
  border-radius: 100% 0 100% 0;
  transform: rotate(35deg);
  pointer-events: none;
}

.leaf-top {
  top: -190px;
  left: 6%;
}

.leaf-bottom {
  right: -110px;
  bottom: -140px;
  width: 300px;
  height: 400px;
  border-color: rgba(128, 224, 82, 0.17);
}

/* Conteúdo lateral */
.side-content {
  position: absolute;
  left: 7%;
  top: 50%;
  transform: translateY(-50%);
  width: 260px;
  z-index: 1;
}

.brand-mini {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 24px;
  font-weight: 750;
  letter-spacing: -1px;
}

.brand-symbol {
  display: grid;
  place-items: center;
  width: 35px;
  height: 35px;
  color: var(--green);
  border: 1px solid rgba(128, 224, 82, 0.6);
  border-radius: 50%;
}

.green-text {
  color: var(--green);
}

.features {
  display: flex;
  flex-direction: column;
  gap: 29px;
  margin-top: 65px;
}

.feature {
  display: flex;
  align-items: center;
  gap: 17px;
  color: #c5d5c6;
  font-size: 12px;
  letter-spacing: 3px;
}

.feature-icon {
  display: grid;
  place-items: center;
  width: 35px;
  height: 35px;
  color: var(--green);
  font-size: 27px;
  letter-spacing: 0;
}

.side-description {
  margin-top: 75px;
  color: #809a85;
  font-size: 14px;
  line-height: 1.9;
}

/* Área do cartão */
.auth-main {
  width: 100%;
  max-width: 440px;
  margin-left: 100px;
  z-index: 2;
}

.auth-card {
  width: 100%;
  box-sizing: border-box;
  padding: 36px 30px 24px;
  border: 1px solid rgba(157, 211, 156, 0.24);
  border-radius: 20px;
  background: rgba(12, 30, 19, 0.76);
  box-shadow:
    0 24px 80px rgba(0, 0, 0, 0.24),
    inset 0 1px 0 rgba(255, 255, 255, 0.035);
  backdrop-filter: blur(18px);
  -webkit-backdrop-filter: blur(18px);
}

/* Marca */
.brand {
  text-align: center;
  margin-bottom: 29px;
}

.brand h1 {
  margin: 0;
  font-size: 43px;
  line-height: 1.2;
  font-weight: 800;
  letter-spacing: -2px;
}

.subtitle {
  margin: 8px 0 0;
  color: var(--muted);
  font-size: 14px;
  letter-spacing: 0.4px;
}

/* Abas */
.tabs {
  display: flex;
  margin-bottom: 27px;
  overflow: hidden;
  border: 1px solid rgba(155, 190, 155, 0.2);
  border-radius: 10px;
}

.tabs button {
  flex: 1;
  min-height: 43px;
  padding: 10px;
  background: transparent;
  color: #b6c6b8;
  border: none;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s, color 0.2s;
}

.tabs button.active {
  background: var(--green);
  color: #07130a;
}

.tabs button:not(.active):hover {
  background: rgba(128, 224, 82, 0.08);
  color: white;
}

/* Campos */
form {
  text-align: left;
}

.form-group {
  margin-bottom: 19px;
}

.form-group label {
  display: block;
  margin-bottom: 9px;
  color: #c5d4c8;
  font-size: 13px;
}

.input-wrapper {
  display: flex;
  align-items: center;
  min-height: 48px;
  box-sizing: border-box;
  padding: 0 13px;
  border: 1px solid rgba(148, 182, 147, 0.23);
  border-radius: 10px;
  background: rgba(3, 13, 7, 0.36);
  transition: border-color 0.2s, box-shadow 0.2s;
}

.input-wrapper:focus-within {
  border-color: var(--green);
  box-shadow: 0 0 0 3px rgba(128, 224, 82, 0.09);
}

.input-icon {
  flex-shrink: 0;
  margin-right: 11px;
  color: #94b69a;
  font-size: 19px;
}

.input-wrapper input {
  width: 100%;
  min-width: 0;
  padding: 13px 0;
  color: white;
  background: transparent;
  border: none;
  outline: none;
  font-family: inherit;
  font-size: 13px;
}

.input-wrapper input::placeholder {
  color: #718475;
}

.toggle-password {
  flex-shrink: 0;
  padding: 5px;
  color: #9db5a0;
  background: transparent;
  border: none;
  cursor: pointer;
  font-size: 18px;
}

/* Botão principal */
.btn-primary {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  width: 100%;
  min-height: 49px;
  margin-top: 25px;
  padding: 12px 18px;
  border: none;
  border-radius: 10px;
  background: linear-gradient(110deg, #8be85d, #75df68);
  color: #07150a;
  font-family: inherit;
  font-size: 14px;
  font-weight: 750;
  cursor: pointer;
  box-shadow: 0 5px 20px rgba(100, 210, 76, 0.08);
  transition: transform 0.2s, filter 0.2s, box-shadow 0.2s;
}

.btn-primary:hover {
  filter: brightness(1.08);
  transform: translateY(-1px);
  box-shadow: 0 8px 25px rgba(100, 210, 76, 0.16);
}

.btn-primary:active {
  transform: translateY(0);
}

/* Mensagens e rodapé */
.alert {
  padding: 12px;
  margin-bottom: 18px;
  border-radius: 9px;
  text-align: left;
  font-size: 13px;
}

.alert-erro {
  color: #ffc3c3;
  border: 1px solid rgba(255, 100, 100, 0.3);
  background: rgba(150, 25, 25, 0.15);
}

.card-footer {
  margin: 25px 0 0;
  color: #78917d;
  text-align: center;
  font-size: 11px;
  letter-spacing: 0.3px;
}

.bottom-caption {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  margin-top: 25px;
  color: #91b197;
  font-size: 10px;
  letter-spacing: 2px;
  text-align: center;
}

.bottom-caption span {
  width: 22px;
  height: 2px;
  background: var(--green);
}

/* Telas menores */
@media (max-width: 1050px) {
  .side-content {
    left: 4%;
    width: 200px;
  }

  .auth-main {
    margin-left: 120px;
  }

  .side-description {
    font-size: 12px;
  }
}

@media (max-width: 760px) {
  .auth-wrapper {
    padding: 25px 18px;
  }

  .side-content {
    display: none;
  }

  .auth-main {
    max-width: 420px;
    margin-left: 0;
  }

  .auth-card {
    padding: 30px 22px 22px;
  }

  .brand h1 {
    font-size: 38px;
  }

  .bottom-caption {
    font-size: 9px;
    letter-spacing: 1.2px;
  }
}

@media (max-width: 380px) {
  .auth-wrapper {
    padding: 16px 12px;
  }

  .auth-card {
    padding: 26px 17px 20px;
  }

  .brand h1 {
    font-size: 34px;
  }
}
</style>
