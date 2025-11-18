// Espera o HTML da página (home.php) ser totalmente carregado
document.addEventListener("DOMContentLoaded", () => {

  // --- 1. Seletores Principais ---
  const cardsContainer = document.querySelector(".cards-container");
  const addCardButton = document.querySelector(".add-card");

  // --- 2. Variáveis Globais do Modal ---
  let modalBackdrop = null;
  let modalContainer = null;

  // --- 3. Funções de Inicialização ---

  /**
   * Função principal: Inicia o setup do modal e carrega as listas.
   */
  function initialize() {
    setupModalShell();
    loadLists();
    setupModalOpeners();
  }

  /**
   * Carrega as listas do banco de dados e as exibe na tela.
   */
  async function loadLists() {
    try {
      // Usa o caminho relativo correto (de Pages/ para Public/)
      const response = await fetch("../actions.php?action=list_get");

      if (!response.ok) {
        throw new Error("Falha ao buscar listas: " + response.statusText);
      }

      const lists = await response.json();

      // Limpa os cards estáticos (exceto o botão "+")
      cardsContainer.innerHTML = "";

      // Adiciona cada lista vinda do banco
      lists.forEach((list) => {
        const card = createListCard(list);
        cardsContainer.appendChild(card);
      });

      // Recoloca o botão "+" no final
      cardsContainer.appendChild(addCardButton);
    } catch (error) {
      console.error(error);
      cardsContainer.innerHTML = "<p>Erro ao carregar as listas.</p>";
      cardsContainer.appendChild(addCardButton);
    }
  }

  /**
   * Cria e injeta o "shell" do modal (fundo e contêiner) no DOM.
   */
  function setupModalShell() {
    // Cria o fundo escuro
    modalBackdrop = document.createElement("div");
    modalBackdrop.className = "modal-backdrop";

    // Cria o contêiner central
    modalContainer = document.createElement("div");
    modalContainer.className = "modal-container";

    // Adiciona ao body
    document.body.appendChild(modalBackdrop);
    document.body.appendChild(modalContainer);
  }

  /**
   * Adiciona os "escutadores" de eventos para abrir e fechar o modal.
   */
  function setupModalOpeners() {
    // Abrir: Clicar no botão "+"
    addCardButton.addEventListener("click", openCreateModal);

    // Fechar: Clicar no fundo escuro
    modalBackdrop.addEventListener("click", closeModal);
  }

  // --- 4. Funções de Lógica do Modal ---

  /**
   * Busca o HTML do create.php e o exibe no modal.
   */
  async function openCreateModal() {
    try {
      // Busca o HTML da página de criação
      const response = await fetch("create.php");

      if (!response.ok) {
        throw new Error("Falha ao carregar o formulário de criação.");
      }

      const modalHTML = await response.text();
      modalContainer.innerHTML = modalHTML;

      // Mostra o modal com animação (CSS)
      modalBackdrop.classList.add("show");
      modalContainer.classList.add("show");

      // Adiciona os "escutadores" aos botões DENTRO do modal
      attachModalListeners();

    } catch (error) {
      console.error(error);
      modalContainer.innerHTML = "<p>Erro ao carregar. Tente novamente.</p>";
    }
  }

  /**
   * Adiciona os listeners aos botões "Cancelar", "Salvar", "Add Item", etc.
   */
  function attachModalListeners() {
    const form = modalContainer.querySelector(".todo-container");
    if (!form) return;

    // Listener para o botão "Salvar Lista"
    form.querySelector(".save-list-btn").addEventListener("click", saveNewList);

    // Listener para o botão "Cancelar"
    form.querySelector(".cancel-btn").addEventListener("click", closeModal);

    // Listener para a tecla "Enter" no input de item
    form.querySelector(".new-item-input").addEventListener("keypress", (e) => {
      if (e.key === "Enter") {
        e.preventDefault(); // Impede o envio de formulário
        addTemporaryTask();
      }
    });
  }

  /**
   * Fecha o modal e limpa seu conteúdo.
   */
  function closeModal() {
    modalBackdrop.classList.remove("show");
    modalContainer.classList.remove("show");
    modalContainer.innerHTML = ""; // Limpa o HTML para a próxima abertura
  }

  /**
   * Adiciona a tarefa apenas na interface (temporariamente)
   * antes de salvar no banco.
   */
  function addTemporaryTask() {
    const input = modalContainer.querySelector(".new-item-input");
    const listArea = modalContainer.querySelector(".todo-list-area");

    const description = input.value.trim();
    if (description === "") return;

    const emptyMsg = listArea.querySelector(".empty-list-message");

    // Remove "Sua lista está vazia"
    if (emptyMsg) emptyMsg.remove();

    // Cria o elemento da nova tarefa (temporário)
    const taskElement = document.createElement("div");
    taskElement.className = "temp-task-item"; // Classe para fácil leitura/remoção
    taskElement.textContent = description;

    listArea.appendChild(taskElement);

    input.value = "";
    input.focus();
  }

  /**
   * Função PRINCIPAL: Salva a lista e, em seguida,
   * salva todas as tarefas associadas.
   */
  async function saveNewList() {
    const titleInput = modalContainer.querySelector(".todo-title-input");
    const title = titleInput.value.trim();

    if (title === "") {
      alert("Por favor, dê um título à sua lista.");
      titleInput.focus();
      return;
    }

    try {
      // --- Passo 1: Salvar a Lista ---
      const listFormData = new FormData();
      listFormData.append("title", title);
      listFormData.append("action", "list_create");

      const listResponse = await fetch("../actions.php", {
        method: "POST",
        body: listFormData,
      });

      const listResult = await listResponse.json();

      if (!listResult.success || !listResult.id) {
        throw new Error(listResult.error || "Falha ao criar a lista.");
      }

      const newListId = listResult.id;

      // --- Passo 2: Salvar as Tarefas ---
      const taskElements = modalContainer.querySelectorAll(".temp-task-item");

      for (const taskElement of taskElements) {
        const fd = new FormData();
        fd.append("action", "task_create");
        fd.append("list_id", newListId);
        fd.append("description", taskElement.textContent);

        await fetch("../actions.php", {
          method: "POST",
          body: fd,
        });
      }

      // --- Passo 3: Fechar e Atualizar ---
      closeModal();
      loadLists();

    } catch (error) {
      console.error(error);
      alert("Erro ao salvar a lista: " + error.message);
    }
  }

  async function deleteList(listId) {
  if (!confirm("Tem certeza que deseja excluir esta lista e todas as suas tarefas?")) {
    return;
  }

  try {
    const fd = new FormData();
    fd.append("action", "list_delete");
    // O backend espera o ID
    fd.append("id", listId); 

    // O caminho CORRETO é ../actions.php
    const response = await fetch("../actions.php", {
      method: "POST",
      body: fd,
    });

    const result = await response.json();

    if (!response.ok || result.error) {
      alert(result.error || "Erro ao deletar lista.");
      return;
    }

    // Atualiza a interface
    loadLists();
  } catch (err) {
    console.error("Erro no deleteList:", err);
    alert("Erro de comunicação ao tentar deletar a lista.");
  }
}

  // --- 5. Funções Auxiliares ---

  // No Public/Assets/JS/home.js:

/**
 * Cria o HTML para um card na home.
 * @param {object} list - O objeto da lista (agora inclui list.tasks)
 * @returns {HTMLElement} - Elemento do card
 */
function createListCard(list) {
    const colors = ["yellow", "blue", "pink", "green", "purple"];
    const color = colors[list.id % colors.length];

    const card = document.createElement("div");
    card.className = `card ${color}`;
    card.dataset.listId = list.id;

    const titleEl = document.createElement("h2");
    titleEl.textContent = list.title;

    // --- Container para as Tarefas (Substitui "(anotações)") ---
    const tasksEl = document.createElement("p");
    tasksEl.className = "card-task-preview";
    
    // Verifica se a lista tem tarefas e exibe o resumo
    if (list.tasks && list.tasks.length > 0) {
        // Exibe o conteúdo da primeira tarefa
        tasksEl.textContent = list.tasks[0].content;
        
        // Se houver mais, adiciona o contador
        if (list.tasks.length > 1) {
            tasksEl.textContent += ` (+${list.tasks.length - 1} mais...)`;
        }
    } else {
        tasksEl.textContent = "(Nenhuma tarefa)";
    }


    // --- Botão de deletar ---
    const deleteBtn = document.createElement("button");
    deleteBtn.className = "delete-btn";
    deleteBtn.innerHTML = "🗑️";
    deleteBtn.title = "Excluir lista";
    deleteBtn.addEventListener("click", (e) => {
        e.stopPropagation(); // Impede abrir modal
        deleteList(list.id);
    });

    card.appendChild(deleteBtn);
    card.appendChild(titleEl);
    card.appendChild(tasksEl); // Adiciona o resumo das tarefas

    return card;
}
  // Inicia a aplicação
  initialize();
});
