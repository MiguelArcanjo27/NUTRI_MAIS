// Ajuste a URL base se seu back-end rodar em outro endereço/porta
const BASE_URL = 'http://localhost:8000/api'

async function chamar(caminho, opcoes = {}) {
  const resposta = await fetch(`${BASE_URL}${caminho}`, {
    credentials: 'include', // envia o cookie de sessão do PHP
    headers: { 'Content-Type': 'application/json' },
    ...opcoes,
  })

  const dados = await resposta.json()

  if (!resposta.ok) {
    throw new Error(dados.erro || 'Erro na requisição')
  }

  return dados
}

export const api = {
  login: (email, senha) =>
    chamar('/auth/login.php', { method: 'POST', body: JSON.stringify({ email, senha }) }),

  cadastrar: (nome, email, senha) =>
    chamar('/auth/cadastro.php', { method: 'POST', body: JSON.stringify({ nome, email, senha }) }),

  logout: () => chamar('/auth/logout.php', { method: 'POST' }),

  eu: () => chamar('/auth/eu.php'),

  listarDietas: () => chamar('/dieta/index.php'),
  criarDieta: (dieta) => chamar('/dieta/index.php', { method: 'POST', body: JSON.stringify(dieta) }),
  excluirDieta: (id) => chamar(`/dieta/index.php?id=${id}`, { method: 'DELETE' }),

  listarTreinos: () => chamar('/treino/index.php'),
  excluirTreino: (id) => chamar(`/treino/index.php?id=${id}`, { method: 'DELETE' }),
  criarTreino: (treino) => chamar('/treino/index.php', { method: 'POST', body: JSON.stringify(treino) }),
  
  
  listarEvolucao: () => chamar('/evolucao/index.php'),
  criarEvolucao: (registro) => chamar('/evolucao/index.php', { method: 'POST', body: JSON.stringify(registro) }),

  listarProfissionais: () => chamar('/profissionais/index.php'),
  criarProfissional: (dados) => chamar('/profissionais/index.php', { method: 'POST', body: JSON.stringify(dados) }),
}
