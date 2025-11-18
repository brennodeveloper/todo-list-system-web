// Espera o HTML da página (home.php) ser totalmente carregado
document.addEventListener("DOMContentLoaded", () => {

  // --- 1. Seletores Principais ---
  const cardsContainer = document.querySelector(".cards-container");
  const addCardButton = document.querySelector(".add-card");

  // --- 2. Variáveis Globais do Modal ---
  let modalBackdrop = null;
  let modalContainer = null;

  // --- 3. Funções de Inicialização ---
  function initialize() {
    setupModalShell();
    loadLists();
    setupModalOpeners();
  }

  async function loadLists() {
    try {
      const response = await fetch("../actions.php?action=list_get");
      if (!response.ok) throw new Error("Falha ao buscar listas: " + response.statusText);

      const lists = await response.json();

      // Limpa o container antes de recarregar
      cardsContainer.innerHTML = ""; 
      lists.forEach((list) => {
        const card = createListCard(list);
        cardsContainer.appendChild(card);
      });
      // Garante que o botão de adicionar fique sempre por último
      cardsContainer.appendChild(addCardButton); 
    } catch (error) {
      console.error(error);
      cardsContainer.innerHTML = "<p>Erro ao carregar as listas.</p>";
      cardsContainer.appendChild(addCardButton);
    }
  }

  function setupModalShell() {
    // Cria o fundo escuro (backdrop)
    modalBackdrop = document.createElement("div");
    modalBackdrop.className = "modal-backdrop";

    // Cria o container do modal
    modalContainer = document.createElement("div");
    modalContainer.className = "modal-container";

    document.body.appendChild(modalBackdrop);
    document.body.appendChild(modalContainer);
  }

  function setupModalOpeners() {
    addCardButton.addEventListener("click", openCreateModal);
    // Permite fechar o modal clicando no fundo (backdrop)
    modalBackdrop.addEventListener("click", closeModal); 
  }

  // --- 4. Funções de Lógica do Modal ---
  async function openCreateModal() {
    try {
      // Carrega o conteúdo do modal a partir do create.php
      const response = await fetch("create.php"); 
      if (!response.ok) throw new Error("Falha ao carregar o formulário de criação.");

      const modalHTML = await response.text();
      modalContainer.innerHTML = modalHTML;

      // Mostra o modal
      modalBackdrop.classList.add("show");
      modalContainer.classList.add("show");

      attachModalListeners();
    } catch (error) {
      console.error(error);
      modalContainer.innerHTML = "<p>Erro ao carregar. Tente novamente.</p>";
    }
  }

  function attachModalListeners() {
    const form = modalContainer.querySelector(".todo-container");
    if (!form) return;

    // Listeners para os botões do formulário
    form.querySelector(".save-list-btn").addEventListener("click", saveNewList);
    form.querySelector(".cancel-btn").addEventListener("click", closeModal);

    // Listener para adicionar tarefa temporária ao pressionar ENTER
    form.querySelector(".new-item-input").addEventListener("keypress", (e) => {
      if (e.key === "Enter") {
        e.preventDefault();
        addTemporaryTask();
      }
    });
  }

  function closeModal() {
    modalBackdrop.classList.remove("show");
    modalContainer.classList.remove("show");
    modalContainer.innerHTML = "";
  }

  function addTemporaryTask() {
    const input = modalContainer.querySelector(".new-item-input");
    const listArea = modalContainer.querySelector(".todo-list-area");

    const description = input.value.trim();
    if (description === "") return;

    const emptyMsg = listArea.querySelector(".empty-list-message");
    if (emptyMsg) emptyMsg.remove();

    const taskElement = document.createElement("div");
    taskElement.className = "temp-task-item";
    taskElement.textContent = description; // Conteúdo da tarefa

    listArea.appendChild(taskElement);

    input.value = "";
    input.focus();
  }

  async function saveNewList() {
    const titleInput = modalContainer.querySelector(".todo-title-input");
    const title = titleInput.value.trim();

    if (title === "") {
      console.warn("Título da lista não informado.");
      titleInput.focus();
      return;
    }

    try {
      // 1. Cria a LISTA (requisição POST para o actions.php)
      const listFormData = new FormData();
      listFormData.append("title", title);
      listFormData.append("action", "list_create");

      const listResponse = await fetch("../actions.php", {
        method: "POST",
        body: listFormData,
      });

      const listResult = await listResponse.json();
      if (!listResult.success || !listResult.id) throw new Error(listResult.error || "Falha ao criar a lista.");

      const newListId = listResult.id;

      // 2. Cria as TAREFAS associadas à nova lista
      const taskElements = modalContainer.querySelectorAll(".temp-task-item");
      for (const taskElement of taskElements) {
        const fd = new FormData();
        fd.append("action", "task_create");
        fd.append("list_id", newListId);
        fd.append("description", taskElement.textContent);

        await fetch("../actions.php", { method: "POST", body: fd });
      }

      // 3. Fecha o modal e recarrega as listas para mostrar o novo card
      closeModal();
      loadLists();
    } catch (error) {
      console.error("Erro ao salvar a lista:", error);
    }
  }

  // --- DELETE LIST (Remove o card do DOM após exclusão no servidor) ---
  async function deleteList(listId) {
    if (!confirm("Tem certeza que deseja excluir esta lista e todas as suas tarefas?")) return;

    try {
      const fd = new FormData();
      fd.append("action", "list_delete");
      fd.append("id", listId);

      const response = await fetch("../actions.php", { method: "POST", body: fd });
      const result = await response.json();

      if (response.ok && !result.error) {
        // Encontra o card pelo seu atributo data-list-id e o remove do DOM
        const card = document.querySelector(`.card[data-list-id="${listId}"]`);
        if (card) card.remove();
      } else {
        console.error("Erro ao deletar lista:", result.error || "Erro desconhecido");
      }
    } catch (err) {
      console.error("Erro de comunicação ao tentar deletar a lista:", err);
    }
  }

  // --- 5. Funções Auxiliares ---
  function createListCard(list) {
    const colors = ["yellow", "blue", "pink", "green", "purple"];
    // Define a cor do card com base no ID da lista para manter a consistência
    const color = colors[list.id % colors.length]; 

    const card = document.createElement("div");
    card.className = `card ${color}`;
    // Atributo essencial para a função deleteList encontrar e remover o card
    card.dataset.listId = list.id; 

    const titleEl = document.createElement("h2");
    titleEl.textContent = list.title;

    const tasksEl = document.createElement("p");
    tasksEl.className = "card-task-preview";

    // Mostra a primeira tarefa e conta as restantes
    if (list.tasks && list.tasks.length > 0) {
      tasksEl.textContent = list.tasks[0].content;
      if (list.tasks.length > 1) {
        tasksEl.textContent += ` (+${list.tasks.length - 1} mais...)`;
      }
    } else {
      tasksEl.textContent = "(Nenhuma tarefa)";
    }

    // Botão de Excluir
    const deleteBtn = document.createElement("button");
    deleteBtn.className = "delete-btn";
    deleteBtn.innerHTML = "🗑️";
    deleteBtn.title = "Excluir lista";
    deleteBtn.addEventListener("click", (e) => {
      e.stopPropagation(); // Impede que o clique no botão ative outro evento (como abrir modal de edição)
      deleteList(list.id);
    });

    card.appendChild(deleteBtn);
    card.appendChild(titleEl);
    card.appendChild(tasksEl);

    return card;
  }

  // Inicia a aplicação
  initialize();
});